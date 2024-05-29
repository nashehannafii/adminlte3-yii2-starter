<?php

namespace app\controllers;

use app\helpers\MyHelper;
use app\models\Auditor;
use app\models\AuthAssignment;
use app\models\UnitKerja;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create', 'update', 'delete', 'index', 'view'],
                'rules' => [

                    [
                        'actions' => ['create', 'update', 'delete', 'index', 'view'],
                        'allow' => true,
                        'roles' => ['theCreator']
                    ]
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User(['scenario' => 'create']);


        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('create', ['user' => $user]);
        }
        $pwd = $user->password;
        $user->setPassword($user->password);
        $user->generateAuthKey();
        // $user->item_name = $user->access_role;
        // $user->display_name = $user->first_name.' '.$user->last_name;


        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        if ($user->validate()) {
            try {


                if ($user->save()) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($user->access_role);
                    $info = $auth->assign($role, $user->getId());

                    if (!$info)
                        throw new \Exception('There was some error while saving user role.');

                    Yii::$app->session->setFlash('success', 'Data successfully added');
                    $transaction->commit();
                } else {
                    $errors = MyHelper::logError($user);
                    Yii::$app->session->setFlash('error', Yii::t('app', $errors));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                $errors = $e->getMessage();


                Yii::$app->session->setFlash('error', Yii::t('app', $errors));
            }
        } else
            return $this->render('create', ['user' => $user]);


        return $this->redirect('index');
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $auth = Yii::$app->authManager;

        // get user role if he has one  
        if ($roles = $auth->getRolesByUser($id)) {
            // it's enough for us the get first assigned role name
            $role = array_keys($roles)[0];
        }

        // if user has role, set oldRole to that role name, else offer 'member' as sensitive default
        $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('member');

        // set property item_name of User object to this role name, so we can use it in our form
        $user->access_role = $oldRole->name;

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('update', ['user' => $user, 'role' => $user->access_role]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }

        // if admin is activating user manually we want to remove account activation token
        if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
            $user->removeAccountActivationToken();
        }

        $user->access_role = $user->access_role;

        if (!$user->save()) {
            return $this->render('update', ['user' => $user, 'role' => $user->access_role]);
        }

        // take new role from the form
        $newRole = $auth->getRole($user->access_role);
        // get user id too
        $userId = $user->getId();

        // we have to revoke the old role first and then assign the new one
        // this will happen if user actually had something to revoke
        if ($auth->revoke($oldRole, $userId)) {
            $info = $auth->assign($newRole, $userId);
        }

        // in case user didn't have role assigned to him, then just assign new one
        if (!isset($role)) {
            $info = $auth->assign($newRole, $userId);
        }

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

        Yii::$app->session->setFlash('success', 'Data successfully updated');
        return $this->redirect(['index']);
    }

    public function actionUpdateAkun()
    {
        $id = Yii::$app->user->identity->id;
        $user = $this->findModel($id);

        $auth = Yii::$app->authManager;

        // get user role if he has one  
        if ($roles = $auth->getRolesByUser($id)) {
            // it's enough for us the get first assigned role name
            $role = array_keys($roles)[0];
        }

        // if user has role, set oldRole to that role name, else offer 'member' as sensitive default
        $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('member');

        // set property item_name of User object to this role name, so we can use it in our form
        $user->access_role = $oldRole->name;

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('update_profil', ['user' => $user, 'role' => $user->access_role]);
        }

        // only if user entered new password we want to hash and save it
        if ($user->password) {
            $user->setPassword($user->password);
        }

        // if admin is activating user manually we want to remove account activation token
        if ($user->status == User::STATUS_ACTIVE && $user->account_activation_token != null) {
            $user->removeAccountActivationToken();
        }

        $user->access_role = $user->access_role;

        if (!$user->save()) {
            return $this->render('update_profil', ['user' => $user, 'role' => $user->access_role]);
        }

        // take new role from the form
        $newRole = $auth->getRole($user->access_role);
        // get user id too
        $userId = $user->getId();

        // we have to revoke the old role first and then assign the new one
        // this will happen if user actually had something to revoke
        if ($auth->revoke($oldRole, $userId)) {
            $info = $auth->assign($newRole, $userId);
        }

        // in case user didn't have role assigned to him, then just assign new one
        if (!isset($role)) {
            $info = $auth->assign($newRole, $userId);
        }

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

        Yii::$app->session->setFlash('success', 'Data successfully updated');
        return $this->redirect(['profil']);
    }

    public function actionAjaxAddRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);

        $auth = Yii::$app->authManager;
        if (!empty($user)) {
            $auth = Yii::$app->authManager;

            $newRole = $auth->getRole($dataPost['item_name']);

            $userId = $user->getId();
            $info = $auth->assign($newRole, $userId);

            $errors = '';


            if (!$info) {
                $errors .= 'There was some error while saving user role.';
                throw new \Exception;
            }


            $results = [
                'code' => 200,
                'message' => 'Data added'
            ];
        } else {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxDeleteRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);


        if (!empty($user)) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $errors = '';
            try {
                $items = AuthAssignment::find()->where([
                    'user_id' => $user->id
                ])->all();

                if (count($items) > 0) {


                    if ($user->access_role != $dataPost['item_name']) {
                        $auth = Yii::$app->authManager;
                        if ($roles = $auth->getRolesByUser($user->id)) {
                            // it's enough for us the get first assigned role name
                            $role = '';
                            foreach ($roles as $r) {

                                if ($r->name == $dataPost['item_name']) {
                                    $role = $r->name;
                                    break;
                                }
                            }
                        }

                        // print_r($roles);exit;

                        // remove role if user had it
                        if (isset($role)) {

                            $info = $auth->revoke($auth->getRole($role), $user->id);
                        }

                        if (!$info) {
                            $errors .= 'Something wrong when deleting role';
                            throw new \Exception;
                        }

                        $transaction->commit();
                        $results = [
                            'code' => 200,
                            'message' => 'Role deleted'
                        ];
                    } else {
                        $errors .= 'Cannot delete assigned role';
                        throw new \Exception;
                    }
                } else {
                    $errors .= 'Cannot delete the last role';
                    throw new \Exception;
                }
            } catch (\Exception $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();

                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            }
        } else {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // delete user or throw exception if could not


        $auth = Yii::$app->authManager;
        $info = true; // monitor info status

        // get user role if he has one  
        if ($roles = $auth->getRolesByUser($id)) {
            // it's enough for us the get first assigned role name
            $role = array_keys($roles)[0];
        }

        // remove role if user had it
        if (isset($role)) {
            $info = $auth->revoke($auth->getRole($role), $id);
        }

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while deleting user role.'));
            return $this->redirect(['index']);
        }

        if (!$this->findModel($id)->delete()) {
            throw new ServerErrorHttpException(Yii::t('app', 'We could not delete this user.'));
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'You have successfuly deleted user and his role.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
