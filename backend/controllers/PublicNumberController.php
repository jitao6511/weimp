<?php
namespace backend\controllers;

use Yii;
use common\models\PublicNumber;
use common\models\PublicNumberSearch;
use backend\base\BaseBackController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

//公众号管理控制器
class PublicNumberController extends BaseBackController
{
    //操作类型控制
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                    'create' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    //显示列表
    public function actionIndex() {
        $query = PublicNumber::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize' => 20]);
        $models = $query->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
         return $this->render('index', [
              'models' => $models,
              'pages' => $pages,
         ]);
    }

    //表单页
    public function actionForm() {
        $id = Yii::$app->request->get('id');
        $model = null;
        if (!empty($id))
            $model = $this->findModel($id);
        return $this->render('form', [
            'model' => $model,
        ]);
    }

    //添加一个公众号
    public function actionCreate() {
        $post = Yii::$app->request->post();
        //判断名称
        if (empty($post['name'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => '公众号名称']));
        }

        //判断类型
        if (empty($post['type'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => '公众号类型']));
        }

        //判断appid
        if (empty($post['appid'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => 'AppID(应用ID)']));
        }

        //判断appsecret
        if (empty($post['appsecret'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => 'AppSecret(应用密钥)']));
        }

        $post['create_time'] = time();
        $post['update_time'] = time();
        $post['url'] = 'http://www.qq.com';
        $post['token'] = 'weixin';
        $model = new PublicNumber();
        if ($model->load(['PublicNumber' => $post]) && $model->save()) {
            $model->order_id = $model->id;
            $model->save();
            return $this->redirect('index');
        } else {
            throw new NotFoundHttpException(Yii::t('yii','An internal server error occurred.'));
        }
    }

    //更新公众号相关信息
    public function actionUpdate() {
        $id = Yii::$app->request->post('id',0);
        if (empty($id))
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => 'ID']));
        $post = Yii::$app->request->post();
        //判断名称
        if (empty($post['name'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => '公众号名称']));
        }

        //判断类型
        if (empty($post['type'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => '公众号类型']));
        }

        //判断appid
        if (empty($post['appid'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => 'AppID(应用ID)']));
        }

        //判断appsecret
        if (empty($post['appsecret'])) {
           throw new NotFoundHttpException(Yii::t('yii','Missing required parameters: {params}',['params' => 'AppSecret(应用密钥)']));
        }

        $model = $this->findModel($id);
        $post['update_time'] = time();
        unset($post['id']);
        if ($model->load(['PublicNumber' => $post]) && $model->save()) {
            return $this->redirect('index');
        } else {
            throw new NotFoundHttpException(Yii::t('yii','An internal server error occurred.'));
        }
    }

    //删除一条公众号
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    //加载模型
    protected function findModel($id) {
        if (($model = PublicNumber::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }
}
