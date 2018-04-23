<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use common\models\AttributesModel;
use common\models\GroupsModel;
use common\models\GoodsModel;
/**
 * 商品控制器
 */
class GoodsController extends BaseController
{
	public function actionIndex()
	{
		return $this->render('index');
	}
	public function actionCreate()
	{
		if ($_POST) {
			$session = Yii::$app->session;
			$params = $_POST;
			$noError = true;
			$models = new GoodsModel;
			if (empty($params['category'])) {
				$session->setFlash('error', '请选择分类');
				$noError = false;
			}
			if (empty($params['goodsName'])) {
				$session->setFlash('error', '请填写商品名称');
				$noError = false;
			}
			if (empty($params['goodsMasterImg'])) {
				$session->setFlash('error', '请上传主图');
				$noError = false;
			}
			
			if (!empty($params['limitation']) && $params['limitation'] == 1) {
				if (empty($params['limitationvalue'])) {
					$session->setFlash('error', '请填写商品限购量');
					$noError = false;
				}
			}
			if ($noError) {
				$result = $models->addGoods($params);
				if ($result) {
					$this->redirect('/goods/index');return;
				}else{
					$session->setFlash('error', '添加失败');
				}
			}

		}
		$groups = (new GroupsModel)->getGroups();
		// json_encode的时候去掉索引
		sort($groups);
		// return $this->render('create', ['groups' =>$groups]);
		return $this->render('vuecreate', ['groups' =>$groups]);
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
		return $this->send(400, '已存在或者添加失败');
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
}