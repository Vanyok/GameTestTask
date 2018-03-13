<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 12.03.18
 * Time: 15:49
 */

namespace app\models;

//This class extends user class from decrium module
class DUser extends \dektrium\user\models\User
{
    private $account = 123456789;

    public function getAccount()
    {
        return $this->account;
    }
}