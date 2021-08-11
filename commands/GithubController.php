<?php


namespace app\commands;

use app\components\GithubComponent;
use yii\console\Controller;

class GithubController extends Controller
{
    public function actionGetRepositories()
    {
        (new GithubComponent())->getRepositories();
    }
}