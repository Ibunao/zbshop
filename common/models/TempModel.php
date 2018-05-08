<?php
namespace common\models;

use yii\base\Object;
/**
* 临时model
*/
class TempModel extends Object
{
	/**
     * 订单号生成，大并发下不能保证唯一
     */
    public function buildOrderNo()
    {
    	/**
    	 * uniqid 获取一个带前缀、基于当前时间微秒数的唯一ID。不保证唯一性
    	 * ord 返回ascii码
    	 */
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}