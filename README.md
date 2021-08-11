## Задание

Есть список пользователей Github, который можно изменять. Нужно сделать страницу, на которой показать 10 самых свежих (по дате обновления) репозиториев этих пользователей. Сами репозитории нужно обновлять каждые 10 минут.
      
Примечание: нужно показывать 10 последних репозиториев из общего списка репозиториев пользователей, а НЕ 10 последних репозиториев каждого из пользователей. 


## Развёртывание

Версия PHP 7.4, Версия postgres 12.7

### 1) Устанавливаем пакеты

    composer install

### 2) Настройка локальных конфигов

Создать примеры и изменить:

    config\db.php
    config\params.php
    web\index.php
    
### 3) Применяем миграции

    php yii migrate

### 4) Сгенерировать токен

Зайти на https://github.com/settings/tokens/new

Полученный токен вставитьв params.php

Пример куда вставлять можно увидеть в params.example.php

### 5) Добавить логины с github'a в params.php

Пример формата можно увидеть в params.example.php

### 6) Добавить задачу в cron

*/10 * * * * cd "Путь в папку с проектом" && php yii github/get-repositories
    
