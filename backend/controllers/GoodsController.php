<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use common\models\AttributesModel;
use common\models\GroupsModel;
use common\models\GoodsModel;
use common\models\SpecModel;
use app\models\search\GoodsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * 商品控制器
 */
class GoodsController extends BaseController
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
     * Lists all GoodsModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionCreate()
	{
		
		$groups = (new GroupsModel)->getGroups();
		// json_encode的时候去掉索引
		sort($groups);
		// return $this->render('create', ['groups' =>$groups]);
		return $this->render('vuecreate', ['groups' =>$groups]);
	}
	public function actionAddGoods()
	{
		if ($_POST) {
			$params = $_POST;
			$noError = true;
			$error = [];
			$models = new GoodsModel;
			if (empty($params['cid'])) {
				$error[] = '请选择分类';
			}
			if (empty($params['goodsNameValue'])) {
				$error[] = '请输入商品名';
			}
			if (empty($params['goodsMasterImgAttr'])) {
				$error[] = '请上传主图';
			}
			
			if (!empty($params['limit']) && $params['limit'] == 1) {
				if (empty($params['limitCount'])) {
					$error[] = '请填写商品限购量';
				}
			}
			if (empty($error)) {
				// echo json_encode($params['specSend']);exit;
				// 添加商品
				$result = $models->addGoods($params);
				if ($result) {
					return $this->redirect('/goods/index');
				}else{
					return $this->send(400, '添加失败');
				}
			}
		}
	}
	/**
	 * 通过分类id获取分类所属的属性
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	public function actionGetAttrs($cid)
	{
		Yii::$app->response->format = 'json';
		$result = (new AttributesModel)->getAttrs($cid);
		if ($result) {
			return $this->send(200, '获取成功', $result);
		}
		return $this->send(400, '没有属性');
	}
	/**
	 * 通过分类id获取分类所属的属性
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	public function actionSetAttr()
	{
		Yii::$app->response->format = 'json';
		$cid = Yii::$app->request->get('cateid');
		$attr = Yii::$app->request->get('attr');
		$cid = trim($cid);
		$attr = trim($attr);
		if (empty($cid) || empty($attr)) {
			return $this->send(400, '请求参数不正确');
		}
		$result = (new AttributesModel)->setAttr($cid, $attr);
		if ($result) {
			return $this->send(200, '添加成功', $result);
		}
		return $this->send(400, '已存在或者添加失败');
	}
	public function actionSetGroup()
	{
		Yii::$app->response->format = 'json';
		$group = Yii::$app->request->get('group');
		$group = trim($group);
		if (empty($group)) {
			return $this->send(400, '请求参数不正确');
		}
		$result = (new GroupsModel)->setGroup($group);
		if ($result) {
			return $this->send(200, '添加成功', $result);
		}
		return $this->send(400, '已存在或者添加失败');
	}
	public function actionAddSpecs()
	{
		Yii::$app->response->format = 'json';
		$cid = Yii::$app->request->get('cid');
		$spec = Yii::$app->request->get('spec');

		if (empty($cid) || empty($spec)) {
			return $this->send(400, '请求参数不正确');
		}
		$result = (new SpecModel)->setSpec($cid, $spec);
		if ($result) {
			return $this->send(200, '添加成功', $result);
		}
		return $this->send(400, '已存在或者添加失败');
	}
	public function actionGetSpecs()
	{
		Yii::$app->response->format = 'json';
		$cid = Yii::$app->request->get('cid');
		$name = Yii::$app->request->get('name');

		if (empty($cid)) {
			return $this->send(400, '请求参数不正确');
		}
		$result = (new SpecModel)->getSpecs($cid, $name);
		if ($result) {
			return $this->send(200, '获取成功', $result);
		}
		return $this->send(400, '空');
	}
	public function actionDeleteSpecs()
	{
		Yii::$app->response->format = 'json';
		$cid = Yii::$app->request->get('cid');
		$sid = Yii::$app->request->get('sid');

		if (empty($cid)) {
			return $this->send(400, '请求参数不正确');
		}
		$result = (new SpecModel)->deleteSpecs($cid, $sid);
		if ($result) {
			return $this->send(200, '删除', $result);
		}
		return $this->send(400, '删除失败');
	}
}