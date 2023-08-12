# libraryGraphql

Инструкция по развёртыванию:

Клонируем репо-рий:

# git clone https://github.com/macheteBoss/LibraryGraphql.git

Заходим в папку с проектом, открываем в терминале, выполняем:

# docker-compose up --build -d

Заходим в контейнер:
# docker exec -it graphql-php-cli bash

Выполняем команды:

# composer install

# php bin/console doctrine:migrations:migrate

# php bin/console doctrine:fixtures:load

Очистить кеш:

# php bin/console cache:clear

Переходим на localhost:8000/graphiql

# Примеры запросов можно найти в корне сайта, файл examples.txt
