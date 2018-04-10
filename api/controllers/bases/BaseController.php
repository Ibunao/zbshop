<?php
namespace api\controllers\bases;

use Yii;
use yii\web\Controller;


class BaseController extends Controller
{
	public $enableCsrfValidation = false;
	public function init()
	{
		Yii::$app->response->format = 'json';
	}
	/**
	 * 发送返回数据
	 * @param  [type] $code  响应码
	 * @param  [type] $msg   相应信息
	 * @param  [type] $other 其他数据
	 * @return [type]        [description]
	 */
	public function send($code = 0, $msg = '', $other = [])
	{
		if (empty($code)) {
			$code = 400;
		}
		if (empty($msg)) {
			$msg = '请求错误';
		}
		$result = ['code' => $code, 'msg' => $msg];
		if ($other) {
			$result['other'] = $other;
		}
		return $result;
	}
}