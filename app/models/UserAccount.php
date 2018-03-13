<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_account".
 *
 * @property int $id
 * @property int $user_id
 * @property double $amount
 * @property string $bank_account
 */
class UserAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_account';
    }

    public static function convertCashToIco($user_id, $amount)
    {
        if (isset($amount) && is_numeric($amount) && $amount > 0) {
            $amount *= Yii::$app->params['convert_rate'];
        } else {
            $amount = 0;
        }
        return UserAccount::addIcoToUserAccount($user_id, $amount);
    }

    public static function addIcoToUserAccount($user_id, $amount)
    {
        $account = UserAccount::findOne(['user_id' => $user_id]);
        if (!isset($account)) {
            $account = new UserAccount();
            $account->user_id = $user_id;
            $account->amount = 0;
        }
        $account->amount += $amount;
        return $account->save(false);
    }

    public static function getAccountInf($user_id)
    {
        if (isset($user_id)) {
            $account = UserAccount::find()->where(['user_id' => $user_id])->one();
            if (isset($account)) {
                return intval($account->amount);
            }
        }
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount'], 'required'],
            [['user_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
        ];
    }
}
