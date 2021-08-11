<?php


namespace app\components;

use app\models\{User, Repository};
use Yii;
use yii\db\Exception;

class GithubComponent
{
    /**
     * @var array
     */
    private array $users;

    /**
     * @var string
     */
    private string $token;

    /**
     * @var
     */
    private $result;

    /**
     * @var array|null
     */
    private ?array $repositories = [];


    /**
     * GithubComponent constructor.
     */
    public function __construct()
    {
        $this->users = Yii::$app->params['github_users'];
        $this->token = $users = Yii::$app->params['access_token'];
    }


    /**
     * Get repositories from github
     */
    public function getRepositories(): void
    {
        $this->unCheckUserNeeds();

        foreach ($this->users as $users) {

            $this->requestForGithub($users);

            if (is_array($this->result)) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $user = $this->findOrCreateUser();

                    foreach ($this->result as $data) {
                        $this->findOrCreateRepository($data, $user);
                    }

                    $this->deleteErasedRepositories($user);
                    $this->checkedUserNeed($user);

                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            }
        }
    }

    /**
     * Removing marks from users who need to be shown
     */
    private function unCheckUserNeeds(): void
    {
        if ($this->users)
            User::updateAll(['need_this_user' => false]);
    }

    /**
     * @param string $user
     */
    private function requestForGithub(string $user): void
    {
        $ch = curl_init('https://api.github.com/users/' . $user . '/repos?sort=updated');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.github.v3',
            'User-Agent:' . $user,
            'access_token:' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $this->result = json_decode(curl_exec($ch));
        curl_close($ch);
    }

    /**
     * @return object
     */
    private function findOrCreateUser(): object
    {
        $login = mb_strtolower($this->result[0]->owner->login);

        $user = User::findOne(['login' => $login]);

        if (!$user) {
            $user = new User();
            $user->login = $login;
            $user->save();
        }

        return $user;
    }

    /**
     * @param object $data
     *
     * @param object $user
     */
    private function findOrCreateRepository(object $data, object $user)
    {
        $repository = Repository::findOne(['name' => $data->name, 'user_id' => $user->id]);

        array_push($this->repositories, $data->name);

        if (!$repository) {
            $repository = new Repository();
            $repository->name = $data->name;
            $repository->user_id = $user->id;
            $repository->repository_updated_at = $data->updated_at;
            $repository->save();
        } else {
            $repository->repository_updated_at = $data->updated_at;
            $repository->save();
        }
    }

    /**
     * @param object $user
     */
    private function deleteErasedRepositories(object $user): void
    {
        if (isset($this->repositories))
            Repository::deleteAll(['and', ['=', 'user_id', $user->id], ['not in', 'name', $this->repositories]]);

    }

    /**
     * @param $user
     */
    private function checkedUserNeed($user)
    {
        User::updateAll(['need_this_user' => true], ['login' => $user->login]);
    }
}