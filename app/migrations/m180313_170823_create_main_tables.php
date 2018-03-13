<?php

use yii\db\Migration;

class m180215_170823_create_main_tables extends Migration
{
    public function safeUp()
    {    

	   $this->createTable('prize', [
	       'id' => $this->primaryKey(),
           'type' => $this->string(),
           'amount' => $this->integer(),
           'description' => $this->string(),
           'status' => $this->integer(),
           'user_id' => $this->integer(),
           'item_id' => $this->integer(),
       ]);

        $this->createTable('user_account', [
            'id' => $this->primaryKey(),
            'bank_account' => $this->string(),
            'amount' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

    }

    public function safeDown()
    {
         // return false;
        $this->dropTable('prize');
        $this->dropTable('user_account');
    }
 
}
