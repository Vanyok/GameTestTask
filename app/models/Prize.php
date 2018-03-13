<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "prize".
 *
 * @property int $id
 * @property int $item_id
 * @property string $type
 * @property double $amount
 * @property string $description
 * @property int $status
 * @property int $user_id
 */
class Prize extends \yii\db\ActiveRecord
{

    const PRIZE_CREATED = 1;
    const PRIZE_IN_BLOCK = 2;
    const PRIZE_DELIVERY_SHEDULED = 3;
    const PRIZE_IN_TRANSFER = 4;
    const PRIZE_DELIVERED = 5;
    const PRIZE_ICO = 'ico';
    const PRIZE_CASH = 'cash';
    const PRIZE_ITEM = 'item';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prize';
    }

    public function getUser()
    {
        return $this->hasOne(DUser::className(), ['id' => 'user_id']);
    }

    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'required'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['status', 'user_id'], 'integer'],
            [['type'], 'string', 'max' => 24],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'description' => 'Description',
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }

    /**
     * generate prize for new lucky man
     * @return null
     */
    public function runGame()
    {
        $allPrizes = $this->getAvailablePrizes();
        if (count($allPrizes) > 0) {
            $num_of_items = count($allPrizes);
            $winner = rand(0, $num_of_items);
            $prize = $allPrizes[$winner];
            $prize->status = Prize::PRIZE_IN_BLOCK;
            $prize->user_id = Yii::$app->user->id;
            $prize->save(false);
            return $prize;
        } else {
            return null;
        }

    }

    public function getAvailablePrizes()
    {
        return array_merge($this->getAvailableIco(), $this->getAvailableCash(), $this->getAvailableItems());
    }

    private function getAvailableIco()
    {

        $step = Yii::$app->params['ico_step'];
        $limit = Yii::$app->params['icoLimit'];
        $start_amount = Yii::$app->params['ico_start_amount'];
        return $this->generateIcoCashSet($start_amount, $limit, $step, Prize::PRIZE_ICO);

    }

    private function generateIcoCashSet($start, $limit, $step, $type)
    {
        $set = [];
        for ($amount = $start; $amount <= $limit; $amount += $step) {
            //We create only one example af each amount - the same chance win it for all
            $set[] = new Prize([
                'type' => $type,
                'amount' => $amount,
                'status' => Prize::PRIZE_CREATED,
            ]);
        };

        return $set;

    }

    /**
     * Cash is limited so get available
     * @return array
     */
    private function getAvailableCash()
    {
        $step = Yii::$app->params['cash_step'];
        $limit = Yii::$app->params['cashLimit'];
        $total = Yii::$app->params['cashTotal'];
        $start_amount = Yii::$app->params['cash_start_amount'];
        $prizes_exist_row = (new \yii\db\Query())->select('sum(amount) as amount')->from('prize')->where(['IN', 'status', [Prize::PRIZE_DELIVERED, Prize::PRIZE_IN_BLOCK, Prize::PRIZE_DELIVERY_SHEDULED]])->one();
        $spent = isset($prizes_exist_row) ? $prizes_exist_row['amount'] : 0;
        $limit = $limit < ($total - $spent) ? $limit : ($total - $spent);
        return $this->generateIcoCashSet($start_amount, $limit, $step, Prize::PRIZE_CASH);

    }

    /**
     * get items which is available
     * @return array
     */
    private function getAvailableItems()
    {
        $set = [];
        $items = Yii::$app->params['bonus_items'];
        $spent_items = Prize::find()->where(['type' => Prize::PRIZE_ITEM])->andWhere(['IN', 'status', [Prize::PRIZE_DELIVERED, Prize::PRIZE_IN_TRANSFER, Prize::PRIZE_IN_BLOCK, Prize::PRIZE_DELIVERY_SHEDULED]])->all();
        foreach ($spent_items as $item) {
            unset($items[$item->item_id]);
        }
        foreach ($items as $key => $item) {
            $set[] = new Prize([
                'type' => Prize::PRIZE_ITEM,
                'description' => $item['name'],
                'item_id' => $key,
                'status' => Prize::PRIZE_CREATED,
            ]);
        }
        return $set;
    }


    /**
     * @return ActiveDataProvider
     * Modify search for admin pages
     */
    public function cashToTransferSearch()
    {
        $prize = new Prize(['type' => Prize::PRIZE_CASH, 'status' => Prize::PRIZE_DELIVERY_SHEDULED]);
        return $prize->search();
    }

    public function itemsToSendSearch()
    {
        $prize = new Prize(['type' => Prize::PRIZE_ITEM, 'status' => Prize::PRIZE_DELIVERY_SHEDULED]);
        return $prize->search();

    }

    public function search()
    {
        $query = Prize::find();
        $query->andFilterCompare('type', $this->type);
        $query->andFilterCompare('status', $this->status);
        $query->andFilterCompare('description', $this->description);
        $query->andFilterCompare('amount', $this->amount);

        return new ActiveDataProvider([
                'query' => $query
            ]
        );
    }



    public function sendItem()
    {
        $this->status = Prize::PRIZE_DELIVERED;
        $this->save(false);
    }

    /**
     * Send http request to bank's API.
     * Lock prize to avoid conclusions
     * @return bool
     */
    public function sendCash()
    {
        $bank_url = Yii::$app->params['bank_url'];
        if (!isset($this->userAccount))
            return false;
        $account = $this->userAccount->getBank_account();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $bank_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('account' => $account,
                "amount" => $this->amount,
                "call_back" => Url::to(['site/callback', 'prize_id' => $this->id]))));

// receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

// further processing ....
        if ($server_output == "OK") {
            $this->status = Prize::PRIZE_IN_TRANSFER;
            $this->save(false);
        } else {
            return false;
        }
        return true;
    }


}
