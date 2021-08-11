<?php

/* @var $this yii\web\View */
/* @var $repositories app\models\Repository */

$this->title = 'Список репозиториев';
?>
<div class="site-index">
    <?php
    if ($repositories) {
        echo "<h3>$this->title</h3>";
        ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Логин</th>
                <th scope="col">Репозиторий</th>
                <th scope="col">Дата обновления</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($repositories as $repository) { ?>
                <tr>
                    <th scope="row"><?= $i ?></th>
                    <td><?= $repository['login'] ?></td>
                    <td>
                        <a href="https://github.com/"<?= $repository['login'] . "/" . $repository['name'] ?>>
                            <?= $repository['name'] ?></a></td>
                    <td><?= $repository['repository_updated_at'] ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
        <?php
    } else {
        echo "<h3>Заполните пользователей. Либо у пользователей нет репозиториев</h3>";
    }
    ?>
</div>
