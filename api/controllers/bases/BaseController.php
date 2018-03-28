<?php
namespace api\controllers\bases;

use Yii;
use yii\web\Controller;


class BaseController extends Controller
{
	public $enableCsrfValidation = false;
}