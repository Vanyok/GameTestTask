<?php

namespace tests\models;

use app\models\DUser;
use app\models\UserAccount;
use PHPUnit\Framework\TestResult;

class UserAccountTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    public $tester;

    private function getTestSet($arr){
        $set = [];
        $rate = \Yii::$app->params('convert_rate');
        $set[] = [
            'val' => 1,
            'exp' => 1*$rate
        ];

        $set[] = [
            'val' => 0,
            'exp' => 0
        ];

        $set[] = [
            'val' => 100,
            'exp' => 100*$rate
        ];

        $set[] = [
            'val' => 3.33,
            'exp' => 3.33*$rate
        ];

        $set[] = [
            'val' => -5,
            'exp' => 0
        ];
        $set[] = [
            'val' => null,
            'exp' => 0
        ];
        $set[] = $arr;
        $set[] = [
            'val' => 'undefined text',
            'exp' => 0
        ];
    }
    public function testConvertCashToIco()
    {
        $test_user = new DUser();
        $test_user->save(false);
        foreach ($this->getTestSet([
            'val' => '',
            'exp' => 0
        ]) as $item){
            expect_that(UserAccount::convertCashToIco($test_user->id,$item['val']));
            $account = UserAccount::findOne(['user_id'=>$test_user->id]);
            expect($account)->exists();
            expect($account->amount)->equals($item['exp']);
            $account->delete();
        }
        $test_user->delete();

    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param TestResult $result
     *
     * @return TestResult
     */
    public function run(TestResult $result = null)
    {
        // TODO: Implement run() method.
    }
}
