<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите данные</p>

    <div class="row">
        <div class="col-lg-5">
            <?php Pjax::begin(); ?>
            <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]], ['id' => 'login-form',]); ?>
            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div style="color:#999;margin:1em 0">
                Вы еще не с нами? <?php echo Html::a('Зарегистрироваться', ['signup']) ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <div class="form-group">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/users/oauth'],
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
