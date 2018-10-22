<?php

namespace php;

use php\models\Prize;
use View;

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 13.03.18
 * Time: 13:39
 */
class Controller

{
    public $view;

    function __construct()
    {

        $this->view = new View();
    }

    public function run()
    {
        if (isset($_GET['a'])) {
            $action = 'action_' . $_GET['a'];
            $this->$action();
        } else {
            $this->action_index();

        }


    }

    public function action_index()
    {
        $this->renderView('index');
    }

    public function action_start_game()
    {
        $prize = (new Prize())->runGame();
        if (isset($prize)) {
            $responce = [
                'status'            => 'success',
                'prize_type'        => $prize->type,
                'prize_amount'      => $prize->amount,
                'prize_id'          => $prize->id,
                'prize_description' => $prize->description,
            ];
        } else {
            $responce = ['status' => false];
        }
        echo json_encode($responce);
    }

    /**
     * Cancel price action
     * */
    public function action_cancel_prize()
    {
        //cancel logic here
        echo json_encode(['status' => 'success',]);
    }

    /**
     * Get prize ajax action
     *
     */

    public function action_get_prize()
    {
        //prize deliver to user logic here
        echo json_encode(['status' => 'success',]);
    }


    /**
     * Convert prize ajax action
     *
     */

    public function action_convert_to_ico()
    {
        //convert cash to icon logic here
        echo json_encode(['status' => 'success',]);
    }

    private function renderView($template = 'template.php')
    {
        $this->view->generate($template);
    }
}