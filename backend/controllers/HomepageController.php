<?php
namespace backend\controllers;

use Yii;
use backend\controllers\bases\BaseController;
use common\models\GroupsModel;
use common\models\HomepageModel;
/**
 * 分类控制器
 */
class HomepageController extends BaseController
{
	public function actionIndex()
	{
		$groups = (new GroupsModel)->getGroups();
		sort($groups);
		$catelist = Yii::$app->params['categories'];
		
		foreach ($catelist as $id => $item) {
			$item['id'] = $id;
			$catelist[$id] = $item;
		}
		sort($catelist);
		$model = HomepageModel::findOne(1);
		$data = [];
		if ($model) {
			$temp = json_decode($model->data, true);
			// var_dump($temp);exit;
			foreach ($temp['carousel'] as $key => $item) {
				$data['title'][] = ['id' => $key+1, 'img' => $item['pic'], 'goodsId' => $item['goodsId']];
			}
			foreach ($temp['groups'] as $key => $item) {
				$data['groups'][] = ['id' => $key+1, 'img' => $item['content'], 'gid' => $item['group'], 'textContent' => $item['textContent']];
			}
			foreach ($temp['classify'] as $key => $item) {
				$data['catelist'][] = ['id' => $key+1, 'name' => $item['name'], 'cid' => $item['cid']];
			}
		}

		// var_dump($data);exit;
		return $this->render('index', ['groups' => $groups, 'catelist' => $catelist, 'data' => $data]);
	}
	public function actionSaveData()
	{
		$title = $_POST['title'];
		$groups = $_POST['groups'];
		$catelist = $_POST['catelist'];
		// var_dump($title, $groups, $catelist);exit;
		$result = [];
		foreach ($title as $key => $item) {
			$result['carousel'][] = ['pic' => $item['img'], 'id' => $item['id'], 'goodsId' => $item['goodsId']];
		}
		foreach ($groups as $key => $item) {
			$result['groups'][] = ['content' => $item['img'], 'textContent' => $item['textContent'], 'group' => $item['gid']];
		}
		foreach ($catelist as $key => $item) {
			$result['classify'][] = ['name' => $item['name'], 'cid' => $item['cid']];
		}
		$model = HomepageModel::findOne(1);
		if ($model) {
			$data = json_decode($model->data, true);
		}else{
			$model = new HomepageModel;
			$data = [];
		}
		$data['carousel'] = $result['carousel'];
		$data['groups'] = $result['groups'];
		$data['classify'] = $result['classify'];
		$model->data = json_encode($data);
		$model->uptime = time();
		$result = $model->save();
		if ($result) {
			echo json_encode(['code' => 200, 'msg' => 'success']);
		}else{
			var_dump($model->errors);
			echo json_encode(['code' => 400, 'msg' => 'success']);
		}
	}
}