<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use PHPExcel;
use PHPExcel_IOFactory;
/**
 * 分类控制器
 */
class CateController extends BaseController
{
	public function actionIndex()
	{

		return $this->render('index');
	}
	/**
	 * 上传文件
	 * @return [type] [description]
	 */
	public function actionFile()
	{
		$postFile = isset($_FILES["catelist"]) ? $_FILES['catelist'] : exit("请上传文件");
        $postFileType = pathinfo($postFile['name'], PATHINFO_EXTENSION);
        $allowExt = array('xls', 'xlsx', 'csv');
        if (empty($postFile)) {
            exit("请上传文件");
        }

        if (!in_array($postFileType, $allowExt)) {
            exit("上传文件不支持类型，仅限传xls后缀名文件,请先下载导入模板再执行操作");
        }
        // var_dump($postFile);exit;
        if (!is_uploaded_file($postFile['tmp_name'])) {
            exit("不是通过HTP POST上传的文件");
        }
        $nowTime = time();
        $newFileName = $nowTime . "." . $postFileType;
        $newFolder = date("Ymd", time());
        $transData = $newFolder . "/" . $newFileName;   //上传文件地址
        $newFolderPath = "upload/" . $newFolder . "/"; //新地址
        // var_dump(Yii::$app->basePath . "/web/" . $newFolderPath);exit;
        // if (!file_exists(Yii::$app->basePath . "/web/" . $newFolderPath)){
        //     mkdir(Yii::$app->basePath . "/web/" . $newFolderPath, 0777);
        // }

        $newFile = Yii::$app->basePath . "/web/" . $newFolderPath . $newFileName;
        if (move_uploaded_file($_FILES["catelist"]['tmp_name'], $newFile)) {

            //xls
            $objPHPExcel = new PHPExcel();
            $objPHPExcel = PHPExcel_IOFactory::load($newFile);
            $result = $objPHPExcel->getActiveSheet()->toArray();
var_dump($result);exit;
		}
	}
}