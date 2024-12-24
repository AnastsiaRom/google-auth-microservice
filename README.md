# Микросервис регистрации и авторизации через Google

Этот проект представляет собой микросервис для регистрации и авторизации пользователей с использованием Laravel, PostgreSQL, PGAdmin и Nginx. Проект разворачивается одной командой `docker-compose up -d` и автоматически проходит тесты.

---

## 1. Компоненты

- **Laravel (php 8.2)**: Основной фреймворк для разработки API.
    
- **PostgreSQL**: База данных для хранения данных пользователей.
    
- **PGAdmin**: Веб-интерфейс для управления PostgreSQL.
    
- **Nginx**: Веб-сервер для обслуживания Laravel.
    
- **Docker**: Контейнеризация всех компонентов.
    

---
## 2. Вводное

Проект разворачивается одной командой:

```bash
	  docker-compose up -d
```

После запуска всех контейнеров автоматически выполняются:

- Миграции базы данных.
    
- Сиды (если есть).
    
- Тесты для проверки функциональности.
    

---

## 3. База данных

### Структура базы данных

- Используется модель `User` с ORM Eloquent.
    
- Поддерживается мягкое удаление (`SoftDeletes`) и временные метки (`timestamps`).
    

### Миграции

Миграции создают таблицу `users` со следующими полями:

- `id`: Уникальный идентификатор пользователя.
    
- `name`: Имя пользователя.
    
- `email`: Уникальный email пользователя.
    
- `password`: Хэшированный пароль.
    
- `google_id`: Идентификатор пользователя, полученный от Google OAuth.
    
- `email_verified_at`: Время подтверждения email.
    
- `remember_token`: Токен для "запоминания" пользователя.
    
- `created_at` и `updated_at`: Временные метки.
    
- `deleted_at`: Поле для мягкого удаления.
    

### Сиды

Если требуется, можно добавить сиды для заполнения базы данных тестовыми данными.

---
## 4. Стратегии авторизации

### 4.1. Локальная авторизация по email и паролю

- Используется стандартная система аутентификации Laravel.
    
- Пользователь может зарегистрироваться, указав email и пароль.
    
- Пользователь может войти в систему, используя email и пароль.
    

### 4.2. Авторизация через Google OAuth 2.0

- Пользователь может войти в систему через Google.
    
- Для этого используется пакет `laravel/socialite`.
    
- После успешной авторизации через Google создается или обновляется пользователь в базе данных.
    

---

## 5. Установка и запуск

### 5.1. Предварительные требования

- Убедитесь, что у вас установлен Docker и Docker Compose.
    
- Убедитесь, что порты `8080` (Nginx), `5432` (PostgreSQL) и `5050` (PGAdmin) свободны.
    
### 5.2. Шаги установки

1. Склонируйте репозиторий:
    
```bash
	git clone https://github.com/AnastsiaRom/google-auth-microservice.git
```

    
2. Создайте файл `.env` на основе `.env.example` и настройте переменные окружения:

```env

    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=google_auth
    DB_USERNAME=laravel
    DB_PASSWORD=secret
    
    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    GOOGLE_REDIRECT_URI=http://localhost:8080/api/auth/google/callback
```

3. Запустите проект с помощью Docker Compose:

```bash
	  docker-compose up -d
```


---

## 6. Доступ к PGAdmin

- **URL**: `http://localhost:5050`
    
- **Email**: `admin@example.com`
    
- **Password**: `secret`

---


## 7. Структура проекта

```
  project/
  ├── laravel/
  │   ├── app/
  │   ├── bootstrap/
  │   ├── config/
  │   ├── database/
  │   ├── public/
  │   ├── resources/
  │   ├── routes/
  │   ├── storage/
  │   ├── tests/
  │   ├── .env
  │   ├── composer.json
  │   ├── composer.lock
  │   ├── artisan
  │   └── ...
  ├── docker-compose.yml
  ├── Dockerfile
  └── nginx.conf
```
