<?php

use app\models\Prodi;
use app\models\UnitKerja;
use app\rbac\models\AuthItem;
use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use richardfan\widget\JSRegister;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */

$list_roles = ArrayHelper::map(AuthItem::getRoles(), 'name', 'name');

?>
<?php foreach (AuthItem::getRoles() as $item_name) : ?>
    <?php $roles[$item_name->name] = $item_name->name ?>
<?php endforeach ?>
<style>
    .modal-dialog {
        top: 50%;
        margin-top: -250px;
    }
</style>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">

                <?php $form = ActiveForm::begin(['id' => 'form-user']); ?>
                <?= $form->errorSummary($user, ['header' => '<div class="alert alert-danger">', 'footer' => '</div>']); ?>
                <?= $form->field($user, 'username')->textInput(
                    ['placeholder' => Yii::t('app', 'Create username'), 'autofocus' => true]
                ) ?>

                <?= $form->field($user, 'uuid')->textInput(
                    ['placeholder' => Yii::t('app', 'UUID')]
                ) ?>

                <?= $form->field($user, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter e-mail')]) ?>

                <?php if ($user->scenario === 'create') : ?>

                <?php endif; ?>

                <div class="form-group">
                    <label>Minimal</label>
                    <select class="form-control select2bs4" style="width: 100%;">
                        <option selected="selected">Alabama</option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                    </select>
                </div>



                <?= $form->field($user, 'status')->dropDownList($user->statusList) ?>

                <div class="form-group">
                    <?= Html::submitButton($user->isNewRecord ? Yii::t('app', 'Create')
                        : Yii::t('app', 'Update'), ['class' => $user->isNewRecord
                        ? 'btn btn-success' : 'btn btn-primary']) ?>

                    <?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>


            <?php
            if (Yii::$app->user->can('theCreator')) {


            ?>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($user->authAssignments as $role) {
                            ?>
                                <tr>
                                    <td><?= $role->item_name; ?></td>
                                    <td><?= Html::a('<i class="fa fa-trash"></i> Remove', 'javascript:void(0)', ['class' => 'btn btn-danger btn-remove-role', 'data-item' => $role->item_name, 'data-user' => $user->id]); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <?= Html::a('<i class="fa fa-plus"></i> Add a Role', 'javascript:void(0)', ['class' => 'btn btn-success', 'id' => 'btn-add-role']); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            <?php
            }
            ?>
        </div>

    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_id" value="<?= $user->id; ?>">
                <?= Html::dropDownList('item_name', '', $list_roles, ['id' => 'item_name', 'class' => 'form-control']); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save">Add this role</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<?php JSRegister::begin(); ?>
<script>
    $(document).on("click", "#btn-add-role", function(e) {
        e.preventDefault()

        $("#exampleModal").modal("show");
    })

    $(document).on('change', '#dosen', function(e) {
        e.preventDefault();
        $("#nim").val($(this).val());
    });

    $(document).on("click", "#btn-save", function(e) {
        e.preventDefault()

        var obj = new Object;
        obj.user_id = $("#user_id").val();
        obj.item_name = $("#item_name").val();

        $.ajax({
            type: 'POST',
            url: "/user/ajax-add-role",
            data: {
                dataPost: obj
            },
            async: true,
            error: function(e) {
                Swal.hideLoading();

            },
            beforeSend: function() {
                Swal.showLoading();
            },
            success: function(data) {
                var hasil = $.parseJSON(data)
                if (hasil.code == 200) {

                    Swal.fire({
                        title: 'Yeay!',
                        icon: 'success',
                        text: hasil.message
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        icon: 'error',
                        text: hasil.message
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            }
        })
    })

    $(document).on("click", ".btn-remove-role", function(e) {
        e.preventDefault()

        var obj = new Object;
        obj.user_id = $(this).data("user");
        obj.item_name = $(this).data("item");

        $.ajax({
            type: 'POST',
            url: "/user/ajax-delete-role",
            data: {
                dataPost: obj
            },
            async: true,
            error: function(e) {
                Swal.hideLoading();
            },
            beforeSend: function() {
                Swal.showLoading();
            },
            success: function(data) {
                var hasil = $.parseJSON(data)
                if (hasil.code == 200) {

                    Swal.fire({
                        title: 'Yeay!',
                        icon: 'success',
                        text: hasil.message
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        icon: 'error',
                        text: hasil.message
                    }).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            }
        })
    })
</script>
<?php JSRegister::end(); ?>