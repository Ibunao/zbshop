<?php

namespace backend\controllers;

use Yii;
use common\models\OrderModel;
use backend\models\search\OrderSearch;
use backend\controllers\bases\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\models\CustomerModel;
use common\models\IntegralsModel;
use yii\db\Query;
/**
 * OrdersController implements the CRUD actions for OrdersModel model.
 */
class OrdersController extends BaseController
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
     * Lists all OrdersModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrdersModel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($orderId)
    {
        $query = (new Query())
            ->select(['g.name', 'oi.num', 'oi.specvalue', 'g.image'])
            ->from('shop_order_items oi')
            ->leftJoin('shop_goods g', 'oi.goodsid = g.id')
            ->where(['orderid' => $orderId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $model = (new OrderModel)->findOne(['order_id' => $orderId]);

        $wuliu = !$model->ship_status;
        return $this->render('items', ['dataProvider' => $dataProvider, 'orderId' => $orderId, 'wuliu' => $wuliu]);
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
    }
    public function actionKuaidi($code, $type, $orderid)
    {
        $model = (new OrderModel)->findOne(['order_id' => $orderid]);
        if (empty($model)) {
            echo json_encode(['code'=>400, 'msg' => '没有此订单']);return;
        }
        $model->ship_no = $code;
        $model->ship_type = $type;
        $model->ship_status = 1;
        if ($model->save()) {
            echo json_encode(['code'=>200, 'msg' => '更新成功']);return;
        }

        echo json_encode(['code'=>200, 'msg' => '更新失败']);
    }
    public function actionTuikuan($orderid)
    {
        $model = (new OrderModel)->findOne(['order_id' => $orderid]);
        if (empty($model)) {
            echo json_encode(['code'=>400, 'msg' => '没有此订单']);return;
        }
        $model->ship_status = 5;

        if ($model->save()) {
            // 减积分 
            $use = $model->integrals;
            $send = floor($model->pay_price);
            $change = $send - $use;

            $customer = CustomerModel::findOne(['openid1' => $model->openid]);
            $old = $customer->integrals;
            $customer->updateCounters(['integrals' => -$change]);
            // 记录积分变动
            $integralsModel = new IntegralsModel;
            $integralsModel->openid = $openid;
            $integralsModel->old = $old;
            $integralsModel->change = $change;
            $integralsModel->new = $old-$change;
            $integralsModel->remark = '退货退积分';
            $integralsModel->create_at = time();
            $integralsModel->save();

            echo json_encode(['code'=>200, 'msg' => '更新成功']);return;
        }

        echo json_encode(['code'=>200, 'msg' => '更新失败']);
    }
    /**
     * Creates a new OrdersModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OrdersModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrdersModel model.
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
     * Finds the OrdersModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdersModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
