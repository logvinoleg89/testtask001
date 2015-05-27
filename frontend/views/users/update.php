<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование пользователя: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <? $status[$model::STATUS_DELETED] = 'Заблокирован';
    $status[$model::STATUS_ACTIVE] = 'Активен';?>
    <?=
    $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
