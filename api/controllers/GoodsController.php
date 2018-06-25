<?php
namespace api\controllers;

use Yii;

use api\controllers\bases\BaseController;
use common\models\CustomerModel;
use common\models\GoodsModel;
/**
 * Site controller
 */
class GoodsController extends BaseController
{
	public function actionGetGoods($type = 'all', $value = '', $page = 1, $orderType = '', $orderSort = '')
	{
		// 暂时只有价格的排序
		$order = [];
		if ($orderType && $orderType == 'price' && $orderSort != 3) {
			$order['wx_price'] = $orderSort == 1 ? SORT_ASC : SORT_DESC;
		}
		if ($type == 'all') {
			return (new GoodsModel)->getGoods($page, [], $order);
		}elseif ($type == 'groups') {
			return (new GoodsModel)->getGoods($page, ['g_id' => $value], $order);
		}elseif ($type == 'classify') {
			$carr = Yii::$app->params['categories'];
			$result = (new GoodsModel)->getCidArr($value, $carr);
			$result[] = $value;
			return (new GoodsModel)->getGoods($page, ['c_id' => $result], $order);
		}elseif ($type == 'search') {
			return (new GoodsModel)->getGoods($page, ['like', 'name', $value], $order);
		}
	}
	public function actionGetGoodsInfo($gid)
	{
		return (new GoodsModel)->getGoodsInfo($gid);
	}
	/**
	 * 存放购物车
	 * @param  [type] $goodsId [description]
	 * @param  [type] $num     [description]
	 * @param  string $specId  [description]
	 * @return [type]          [description]
	 */
	public function actionSetCart($goodsId, $num, $specId = '')
	{
		return ['code' => 200];
	}
	public function actionTest()
	{
		$carr = Yii::$app->params['categories'];
		$result = (new GoodsModel)->getCidArr(1, $carr);
		var_dump($result);exit;
	}
}