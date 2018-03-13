<?php

namespace php\models;

require_once 'FileDataModel.php';
use Params;


class Prize extends FileDataModel
{

    public  $id;
    public$item_id;
    public$type;
    public $amount;
    public$description;
    public$status;
    public$user_id;
    private $fileName = __DIR__.'/../data/prize_data.json';

    const PRIZE_CREATED = 1;
    const PRIZE_IN_BLOCK = 2;
    const PRIZE_DELIVERY_SHEDULED = 3;
    const PRIZE_IN_TRANSFER = 4;
    const PRIZE_DELIVERED = 5;
    const PRIZE_ICO = 'ico';
    const PRIZE_CASH = 'cash';
    const PRIZE_ITEM = 'item';



    public function getRezervedPrizes(){
        $data = json_decode(file_get_contents($this->fileName),true);
        $res = [];
        if(is_array($data)){
            foreach ($data as $record){
                if(($record['status'] == Prize::PRIZE_IN_BLOCK ||
                        $record['status'] == Prize::PRIZE_DELIVERY_SHEDULED ||
                        $record['status'] == Prize::PRIZE_IN_TRANSFER ||
                        $record['status'] == Prize::PRIZE_DELIVERED ) &&  $record['type']==Prize::PRIZE_ITEM){
                    $res[] = new Prize(
                        ['status' => $record['status'],
                            'item_id' => $record['item_id'],
                            'description' => $record['description']]
                    );
                }
            }
        }

        return $res;
    }

    public function save(){
        $record = ['item_id'=>$this->item_id,
            'type'=>$this->type,
            'amount'=>$this->amount,
            'description'=>$this->description,
        'status'=>$this->status,
        'user_id'=>$this->user_id];
        $data = json_decode(file_get_contents($this->fileName));
        $data[] = $record;
        file_put_contents($this->fileName,json_encode($data));
    }

    public function getRezervedCash(){
        //There should be logic which calculate all delivered cash
        return 100;
    }

    public function getUser()
    {
        return new DUser();
    }





    private function getAvailableIco(){

        $step = Params::$app_params['ico_step'];
        $limit =  Params::$app_params['icoLimit'];
        $start_amount =  Params::$app_params['ico_start_amount'];
        return $this->generateIcoCashSet($start_amount,$limit,$step,Prize::PRIZE_ICO);

    }

    private function generateIcoCashSet($start,$limit,$step, $type){
        $set = [];
        for ($amount = $start; $amount <= $limit; $amount += $step){
            //We create only one example af each amount - the same chance win it for all
            $set[] = new Prize([
                'type' => $type,
                'amount' => $amount,
                'status' => Prize::PRIZE_CREATED,
            ]);
        };

        return $set;

    }

    private function getAvailableCash()
    {
        $step = Params::$app_params['cash_step'];
        $limit = Params::$app_params['cashLimit'];
        $total = Params::$app_params['cashTotal'];
        $start_amount = Params::$app_params['cash_start_amount'];
        $spent = $this->getRezervedCash();
        $limit = $limit < ($total-$spent) ? $limit : ($total - $spent);
        return $this->generateIcoCashSet($start_amount,$limit,$step,Prize::PRIZE_CASH);

    }

    private function getAvailableItems(){
        $set = [];
        $items = Params::$app_params['bonus_items'];
        $spent_items = $this->getRezervedPrizes();
        foreach ($spent_items as $item){
            unset($items[$item->item_id]);
        }
        foreach ($items as $key=>$item){
            $set[] = new Prize([
                'type' => Prize::PRIZE_ITEM,
                'description' => $item['name'],
                'item_id'=>$key,
                'status' => Prize::PRIZE_CREATED,
             ]);
        }
        return $set;
    }

    public function getAvailablePrizes(){
        return array_merge($this->getAvailableIco(),$this->getAvailableCash(),$this->getAvailableItems());
    }

    public function runGame(){
        $allPrizes = $this->getAvailablePrizes();
        if(count($allPrizes) > 0){
            $num_of_items = count($allPrizes);
            $winner = rand(0,$num_of_items);
            $prize = $allPrizes[$winner];
            $prize->status = Prize::PRIZE_IN_BLOCK;
            $prize->user_id = DUser::getCurrentUserId();
            $prize->save();
            return $prize;
        }else{
            return null;
        }

    }



    public function sendItem(){
        $this->status = Prize::PRIZE_DELIVERED;
        $this->save();
    }

    public function sendCash(){
        $bank_url = Params::$app_params['bank_url'];
        if(!isset($this->userAccount))
            return false;
        $account = $this->getUser()->getAccount();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$bank_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('account' => $account,
                "amount"=>$this->amount,
                "call_back"=>"site/callback")));

// receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

// further processing ....
        if ($server_output == "OK") {
            $this->status = Prize::PRIZE_IN_TRANSFER;
            $this->save();
        } else { return false ;}
        return true;
    }


}
