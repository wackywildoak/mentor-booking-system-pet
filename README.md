# mentor-booking-system-pet
# REST API система бронирования платных занятий с менторами

## Описание

**Стек**
- PHP 8.4+
- MySQL 8+
- ORM Doctrine 3
- PHP-DI
- Bramus Router

**Зависимости**
```
"php": ">=8.1",
"bramus/router": "^1.6",
"doctrine/orm": "^3",
"doctrine/dbal": "^4",
"symfony/cache": "^7",
"firebase/php-jwt": "^7.0",
"php-di/php-di": "^7.1",
"vlucas/phpdotenv": "^5.6"
```

**Архитектура** - Clean Architecture | DDD
---

## Установка
### Клонировать репозиторий
- Открыть терминал или командную строку
- Перейти к директории где будет храниться проект
- Склонировать репозиторий

```zsh
git clone https://github.com/wackywildoak/mentor-booking-system-pet.git
```

### Установка зависимостей
- Установить зависимости через composer
```zsh
composer install
```

### Настройка
- Скопировать пример .env файла
```zsh
cp .env.example .env
```

- Указать корректные креды для подключения к вашей БД
```env
DB_HOST=mysql
DB_PORT=3306
DB_NAME=database
DB_USER=root
DB_PASSWORD=root
```