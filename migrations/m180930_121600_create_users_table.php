<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m180930_121600_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
			'first_name' => $this->string(50)->null(),
			'last_name' => $this->string(50)->null(),
			'email' => $this->string(64)->unique()->notNull(),
			'balance' => $this->integer(),
			'token' => $this->string(32),
			'token_expiration_date' => $this->dateTime('Y-m-d H:i:s'),
			'api_key' => $this->string(32),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
