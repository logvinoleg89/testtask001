<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('frontend', 'Registration');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('frontend', 'Please fill out the form') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php Pjax::begin(); ?>
            <?php $form = ActiveForm::begin(
                ['options' => ['data-pjax' => true]],
                ['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('frontend', 'Sign up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <div class="form-group">
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/users/oauth']
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
