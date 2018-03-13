<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 12.03.18
 * Time: 15:49
 */

namespace php\models;

//This class extends user class from decrioum module
class DUser
{
    private $account = 123456789;
    public function getAccount(){
        return $this->account;
    }

    public static function getCurrentUserId(){
        return 1;
    }


}