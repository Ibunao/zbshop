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
		}elseif ($type = 'group') {
			return (new GoodsModel)->getGoods($page, ['gid' => $value], $order);
		}elseif ($type = 'cat') {
			return (new GoodsModel)->getGoods($page, ['c_id' => $value], $order);
		}
	}
	public function actionGetGoodsInfo($gid)
	{
		return (new GoodsModel)->getGoodsInfo($gid);
	}
	public function actionSetCart($goodsId, $num, $specId = '')
	{
		return ['code' => 200];
	}
}