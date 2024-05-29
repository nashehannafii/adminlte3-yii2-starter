<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\rbac\models\AuthItem $model */

$this->title = Yii::t('app', 'Update Auth Item: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
