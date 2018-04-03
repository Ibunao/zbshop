<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use backend\models\search\AgentSearch;
use common\models\AgentUserModel;
/**
 * 商品控制器
 */
class AgentController extends BaseController
{

	public function actionIndex()
	{
		$searchModel = new AgentSearch(); 
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); 

        return $this->render('index', [ 
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider, 
        ]); 
	}

    /** 
     * Displays a single AgentUserModel model. 
     * @param string $id
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
     * Creates a new AgentUserModel model. 
     * If creation is successful, the browser will be redirected to the 'view' page. 
     * @return mixed 
     */ 
    // public function actionCreate() 
    // { 
    //     $model = new AgentUserModel(); 

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) { 
    //         return $this->redirect(['view', 'id' => $model->id]); 
    //     } 

    //     return $this->render('create', [ 
    //         'model' => $model, 
    //     ]); 
    // } 

    /** 
     * Updates an existing AgentUserModel model. 
     * If update is successful, the browser will be redirected to the 'view' page. 
     * @param string $id
     * @return mixed 
     * @throws NotFoundHttpException if the model cannot be found 
     */ 
    public function actionUpdate($id, $status) 
    { 
        $model = $this->findModel($id); 
        $model->status = $status;
        $model->updated_at = time();
        if ($model->save(false)) { 
            return $this->redirect(['view', 'id' => $model->id]); 
        } 

        var_dump($model->errors);
    } 

    /** 
     * Finds the AgentUserModel model based on its primary key value. 
     * If the model is not found, a 404 HTTP exception will be thrown. 
     * @param string $id
     * @return AgentUserModel the loaded model 
     * @throws NotFoundHttpException if the model cannot be found 
     */ 
    protected function findModel($id) 
    { 
        if (($model = AgentUserModel::findOne($id)) !== null) { 
            return $model; 
        } 

        throw new NotFoundHttpException('The requested page does not exist.'); 
    } 
}