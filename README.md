# Гороно - опросник выпускников

## Требования

- SSL сертификат
- php8.2
- nginx (Настройка nginx: <a href="https://laravel.com/docs/11.x/deployment#nginx">ссылка</a>)
- postgres/mysql (возможная последняя версия)
- composer

### php-extensions

- php8.2-redis
- php8.2-ctype
- php8.2-curl
- php8.2-dom
- php8.2-fileinfo
- php8.2-filter
- php8.2-hash
- php8.2-mbstring
- php8.2-openssl
- php8.2-pcre
- php8.2-pdo
- php8.2-session
- php8.2-tokenizer
- php8.2-xml
- php8.2-zip
- php8.2-gd2
- php8.2-iconv
- php8.2-simplexml
- php8.2-xmlreader
- php8.2-zlib

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

### Оптимизация

```bash
php artisan optimize
```
> Оптимизация должна быть в конце
