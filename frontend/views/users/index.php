<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-success">' . $message . '</div>';
    }
    ?>
    <?php \yii\widgets\Pjax::begin(); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                        return $model->status == 10 ? Yii::t('frontend', 'Active') : Yii::t('frontend', 'Locked');
                    },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'],
        ],
    ]);
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
