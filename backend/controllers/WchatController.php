<?php

namespace backend\controllers;

use Yii;
use common\models\WxOther;
use backend\models\search\WxOtherSearch;
use backend\controllers\bases\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * WchatController implements the CRUD actions for WxOther model.
 */
class WchatController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all WxOther models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WxOtherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WxOther model.
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
     * Creates a new WxOther model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WxOther();
        if(Yii::$app->request->post()){
        	$model->load(Yii::$app->request->post());
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if($path = $model->upload()){
                //文件上传成功
                // var_dump($path);exit;
                $model->img = $path;

                if($model->save(false)){
                	return $this->redirect(['index']);
                }
            }
        }
        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WxOther model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->post()){
        	$model->load(Yii::$app->request->post());
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if($path = $model->upload()){
                //文件上传成功
                // var_dump($path);exit;
                $model->img = $path;

                if($model->save(false)){
                	return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WxOther model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WxOther model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WxOther the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WxOther::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionTest()
    {
    	$data = (new WxOtherSearch)->getItems();
    	var_dump($data);exit;
    }
}
