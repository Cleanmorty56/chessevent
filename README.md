# ♔ Шахматы онлайн ♚

Веб-приложение для игры в шахматы в реальном времени с рейтинговой системой ELO, турнирами и чатом.

## 🎯 Возможности

- ✅ Игра в реальном времени через WebSocket
- ✅ Поиск соперника автоматически или по ссылке
- ✅ Рейтинговая система ELO
- ✅ История игр и статистика профиля
- ✅ Чат во время игры
- ✅ Предложение и принятие ничьей
- ✅ Сдача партии
- ✅ Скачивание партий в формате PGN
- ✅ Турниры и жеребьёвка
- ✅ Адаптивный дизайн

## 🚀 Технологии

| Компонент | Технология |
|-----------|------------|
| Бэкенд | Yii2 (PHP 8.2) |
| WebSocket | Workerman |
| База данных | MySQL |
| Фронтенд | jQuery, Chess.js, Chessboard.js, Bootstrap 5 |
| Сервер | Apache + mod_proxy_wstunnel |

## 📦 Установка

### Требования
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js (для сборки)

Требования к серверу
Домен	chess-event.kpk45.ru
Порт WebSocket	8085 (внутренний)
Прокси	/ws → ws://127.0.0.1:8085
Модули Apache	mod_proxy, mod_proxy_wstunnel

Логины и пароли админа и одного из пользователей:
AdminChess - admin123
EgorChesser1778 - egor1778

### Шаги

```bash
# Клонировать репозиторий
git clone https://github.com/Cleanmorty56/chess-event.git
cd chess-event

# Установить зависимости
composer install

# Настроить базу данных
# Создайте базу данных и импортируйте diplom_lakaev.sql

# Настроить конфигурацию
cp config/db.php.example config/db.php
# Отредактируйте config/db.php (укажите доступ к БД)

# Запустить WebSocket сервер
php websocket/daemon.php start
