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
    /** @var View  instance of View class */
    protected $view;

    function __construct($view)
    {

        $this->view = $view;
    }

    protected function actions(){
        return ['index','start_game','cancel_prize','get_prize','convert_to_ico'];
    }
    public function run()
    {
        $actionName = isset($_GET['a']) ? $_GET['a'] : '';
        if (!in_array($actionName,$this->actions())) {
            $this->action_index();
        }
        $action = 'action_' . $actionName;
        $this->$action();



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
        $this->view->generateJson($responce);
    }

    /**
     * Cancel price action
     * */
    public function action_cancel_prize()
    {
        //cancel logic here
        $this->view->generateJson(['status' => 'success']);
    }

    /**
     * Get prize ajax action
     *
     */

    public function action_get_prize()
    {
        //prize deliver to user logic here
        $this->view->generateJson(['status' => 'success']);

    }


    /**
     * Convert prize ajax action
     *
     */

    public function action_convert_to_ico()
    {
        //convert cash to icon logic here
        $this->view->generateJson(['status' => 'success']);
    }

    private function renderView($view, $template = 'template.php')
    {
        $this->view->generate($view, $template);
    }


}