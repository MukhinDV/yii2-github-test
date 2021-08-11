<?php


namespace app\models\query;


use app\models\Repository;

class RepositoryQuery
{
    public static function getLastRepositories()
    {
        return Repository::find()
            ->select(['repository.*', 'user.login'])
            ->leftJoin("user", '"user".id = repository.user_id')
            ->where(['user.need_this_user' => true])
            ->orderBy('repository_updated_at DESC')->limit(10)->asArray()->all();
    }
}