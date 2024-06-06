<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## План действий

- Клонируем проект локально
- Запускаем довер на устройстве
- Устанавливаем PHP и Composer (если ранее не были установлены)
- Запускаем команды в терминале по очереди

```bash
mv .env.example .env
```

```bash
composer i
```

```bash
php artisan key:generate
```

```bash
sail up -d
```

```bash
sail artisan migrate --seed
```

```bash
sail artisan scribe:generate
```

Готово
<br><br>
Проект доступен по адресу: http://localhost
<br>
Документация по API: http://localhost/docs
