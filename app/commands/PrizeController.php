<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 13.03.18
 * Time: 12:09
 */

namespace app\commands;


use app\models\Prize;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class PrizeController
 * @package app\commands
 *
 * Console command for send cash prize batch quantity = n
 *
 */
class PrizeController extends Controller
{

    public function actionSendCash($n = 10)
    {
        $prizes = Prize::find()->where(['type' => Prize::PRIZE_CASH, 'status' => Prize::PRIZE_DELIVERY_SHEDULED])->limit($n)->all();
        foreach ($prizes as $prize) {
            $prize->sendCash();
        }
        return ExitCode::OK;
    }

}