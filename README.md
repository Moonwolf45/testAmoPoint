<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Миграция
php artisan migrate

### Тестовый запуск команды
php artisan posts:fetch

### Проверка расписания
php artisan schedule:list

### Запуск планировщика (в продакшене — через systemd/cron)
php artisan schedule:work

## Альтернативные алгоритмы и почему они не выбраны

<table>
    <thead>
        <tr>
            <th>Альтернатива</th>
            <th>Почему не выбрана</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>data-type="personal" на div или input</td>
            <td>Чистее архитектурно, но в ТЗ чётко указано: «в атрибуте name которых есть значение». Решение строго следует условию.
        </td>
        </tr>
        <tr>
            <td>Удаление/вставка DOM-узлов (remove() / insertAdjacentHTML)</td>
            <td>Уничтожает введённые данные, ломает валидацию браузера, требует восстановления состояния при переключении назад.</td>
        </tr>
        <tr>
            <td>CSS :has() + peer-checked</td>
            <td>CSS не умеет реагировать на change в select без JS-интервенции (добавления класса на форму). Это усложнение без выигрыша.</td>
        </tr>
        <tr>
            <td>Прямое изменение style.display</td>
            <td>Работает, но CSS-класс позволяет легко переопределить поведение (анимации, visibility, grid-area) без правки JS.</td>
        </tr>
        <tr>
            <td>jQuery / Vue / React</td>
            <td>Добавляют 30–150 КБ зависимостей ради 15 строк логики. Ванильный JS покрывает задачу нативно и быстрее.</td>
        </tr>
        <tr>
            <td>MutationObserver</td>
            <td>Переусложнение. Форма статична, нам нужно реагировать только на одно событие change.</td>
        </tr>
    </tbody>
</table>

## Подключение на любом сайте

``<script src="https://yourdomain.com/js/analytics-tracker.js" defer></script>``
