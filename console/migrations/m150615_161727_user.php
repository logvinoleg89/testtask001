<?php

use yii\db\Schema;
use yii\db\Migration;

class m150615_161727_user extends Migration
{
    public function up()
    {

        $this->alterColumn('{{%user}}', 'username', Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'oauth_client', Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'oauth_client_user_id', Schema::TYPE_STRING);
        $this->addColumn('{{%user}}', 'role', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 10');
        
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
        $this->alterColumn('{{%user}}', 'username', Schema::TYPE_STRING . ' NOT NULL');
        $this->dropColumn('{{%user}}', 'oauth_client');
        $this->dropColumn('{{%user}}', 'oauth_client_user_id');
        $this->dropColumn('{{%user}}', 'role');
    }
}
