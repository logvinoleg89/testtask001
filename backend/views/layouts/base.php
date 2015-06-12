<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\BackendAsset::register($this);

$this->params['body-class'] = array_key_exists('body-class', $this->params) ?
    $this->params['body-class']
    : null;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <?php echo Html::csrfMetaTags() ?>
    <title><?php echo Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head> 
<body class=" skin-blue   " cz-shortcut-listen="true">
    <?php $this->beginBody() ?>
        <?php echo $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>