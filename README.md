## Требования

- PHP >= 8.2
- MySQL
- Composer
- PDO драйвер для PHP

## Установка

1. **Клонировать репозиторий:**

    ```bash
    git clone https://github.com/heaset/php-crud-app-UrFU.git
    cd php-crud-app-UrFU
    ```

2. **Установить зависимости с помощью Composer:**

    ```bash
    composer install
    ```

3. **Обновить зависимости:**

    ```bash
    composer update
    ```

4. **Настройка базы данных:**

    Переименуйте файл `.env.example` в `.env` и настройте параметры подключения к базе данных:

    ```bash
    mv .env.example .env
    ```

5. **Запуск сервера:**

    (Показан запуск локального сервера)

    ```bash
    php -S localhost:8000 -t public
    ```

    После этого проект будет доступен по адресу `http://localhost:8000`.
