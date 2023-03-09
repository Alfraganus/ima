<?php

use \yii\db\Migration;

class m190123_110200_create_users_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(200),
            'email'=>$this->string(150)->null(),
            'full_name'=>$this->string(255),
            'birth_date'=>$this->date()->null(),
            'mob_phone_no'=>$this->string(150)->null(),
            'pin'=>$this->string(150)->null(),
            'pport_issue_date'=>$this->date()->null(),
            'pport_expr_date'=>$this->date()->null(),
            'pport_issue_place'=>$this->string(150)->null(),
            'pport_no'=>$this->string(150)->null(),
            'ctzn'=>$this->string(150)->null(),
            'birth_place'=>$this->string(255)->null(),
            'per_adr'=>$this->string(255)->null(),
            'is_active'=>$this->boolean()->null(),
            'registered_time'=>$this->dateTime()->defaultExpression('NOW()'),
            'auth_key'=>$this->string(255)->null(),
        ]);

        $this->createTable('{{%expert_user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%expert_user}}');
    }
}
