# Гороно - опросник выпускников

## Требования

- SSL сертификат
- php8.2
- nginx (Настройка nginx: <a href="https://laravel.com/docs/11.x/deployment#nginx">ссылка</a>)
- postgres/mysql (возможная последняя версия)
- composer

### php-extensions

- php-redis
- php-ctype
- php-curl
- php-dom
- php-fileinfo
- php-filter
- php-hash
- php-mbstring
- php-openssl
- php-pcre
- php-pdo
- php-session
- php-tokenizer
- php-xml
- php-zip
- php-gd2
- php-iconv
- php-simplexml
- php-xmlreader
- php-zlib

## Установка

### Загрузите из git:

```bash
git clone http://git.tashkent.uz/ibodullaev.firdavs/gorono_bot.git
```

### Запустите composer

```bash
composer install --optimize-autoloader --no-dev
```

### Создайте файл .env

```bash
cp .env.example .env
```

### Сгенерируйте ключ

```bash
php artisan key:generate
```

### Конфигурация .env

В файле `.env`

```dotenv
APP_NAME="Bitiruvchi 2024"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Tashkent
APP_URL=domain
```

#### Конфигурация базы данных

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

#### Токен телеграм бота

```dotnev
TELEGRAM_BOT_TOKEN=6943535856:AAG7J9lhill3dKPDkBrcpWPtwb07vUCcIIw
```

> Если база данных MySql, `DB_CONNECTION=mysql`

### Загрузка списка районов, школ и ВУЗов

```bash
php artisan app:load-schools-from-excel
```

```bash
php artisan app:load-universities-from-excel
```


### Оптимизация

```bash
php artisan optimize
```
> Оптимизация должна быть в конце
