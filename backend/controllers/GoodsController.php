<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use common\models\AttributesModel;
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
		return $this->render('create');
	}
	/**
	 * 通过分类id获取分类所属的属性
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	public function actionGetAttrs($cid)
	{
		Yii::$app->response->format = 'json';
		return (new AttributesModel)->getAttrs($cid);
	}
	/**
	 * 通过分类id获取分类所属的属性
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	public function actionSetAttr($attr)
	{
		Yii::$app->response->format = 'json';
		return (new AttributesModel)->getAttrs($cid);
	}
}