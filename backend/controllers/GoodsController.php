<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use common\models\AttributesModel;
use common\models\GroupsModel;
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
		$groups = (new GroupsModel)->getGroups();
		return $this->render('create', ['groups' =>$groups]);
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
}