<?php

use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">

        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title">Login TransTwin</h3>
            </div>
            <div class="card-body">

                <p class="login-box-msg">Sign in to start your session</p>
                <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

                <label for="loginUsername">Username</label>
                <?= $form->field($model, 'username', [
                    'options' => ['class' => 'form-group has-feedback', 'id' => "loginUsername"],
                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                    'wrapperOptions' => ['class' => 'input-group mb-3']
                ])
                    ->label("Username")
                    ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

                <label for="loginPassword">Password</label>
                <?= $form->field($model, 'password', [
                    'options' => ['class' => 'form-group has-feedback', 'id' => "loginPassword"],
                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                    'wrapperOptions' => ['class' => 'input-group mb-3']
                ])
                    ->label("Password")
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
            </div>

            <div class="card-footer">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary']) ?>

                <?php \yii\bootstrap4\ActiveForm::end(); ?>
            </div>

        </div>
    </div>
    <div class="col-md-3"></div>
</div>