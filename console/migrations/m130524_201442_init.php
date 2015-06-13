<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING,
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            
            'oauth_client' => Schema::TYPE_STRING,
            'oauth_client_user_id' => Schema::TYPE_STRING,
            'role' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "User"',

            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
        
        $this->insert('{{%user}}', [
            'id'=>1,
            'username'=>'admin',
            'email'=>'admin@example.com',
            'password_hash'=>Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'auth_key'=>Yii::$app->getSecurity()->generateRandomString(),
            'role'=>\common\models\User::ROLE_ADMIN,
            'status'=>\common\models\User::STATUS_ACTIVE,
            'created_at'=>time(),
            'updated_at'=>time()
        ]);
        
        $this->insert('{{%user}}', [
            'id'=>2,
            'username'=>'user',
            'email'=>'user@example.com',
            'password_hash'=>Yii::$app->getSecurity()->generatePasswordHash('user'),
            'auth_key'=>Yii::$app->getSecurity()->generateRandomString(),
            'role'=>\common\models\User::ROLE_USER,
            'status'=>\common\models\User::STATUS_ACTIVE,
            'created_at'=>time(),
            'updated_at'=>time()
        ]);
    }
    
    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
