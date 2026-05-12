<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\TcpConnection;

// Подключаем Yii2
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

// СОЗДАЁМ ВЕБСОКЕТ СЕРВЕР
// Пробуем разные порты по очереди
$ports = [8080, 8085, 8090, 3000];
$port = null;

foreach ($ports as $p) {
    $test = @fsockopen('127.0.0.1', $p, $errno, $errstr, 1);
    if (!$test) {
        $port = $p;
        break;
    }
    if ($test) fclose($test);
}

if (!$port) {
    die("❌ Нет свободных портов!\n");
}

echo "✅ Используем порт: {$port}\n";

// Создаём Worker
$worker = new Worker("websocket://0.0.0.0:{$port}");
$worker->count = 1;

// Хранилища
$rooms = [];
$waitingPlayers = [];
$roomCounter = 1000;

// При подключении
$worker->onConnect = function($connection) {
    $connection->userId = null;
    $connection->username = null;
    $connection->roomId = null;
    echo "✅ Новый клиент подключился\n";
};

// При получении сообщения
$worker->onMessage = function($connection, $data) use (&$rooms, &$waitingPlayers, &$roomCounter) {
    $msg = json_decode($data, true);
    if (!$msg) return;

    switch($msg['type']) {
        case 'register':
            $connection->userId = $msg['userId'];
            $connection->username = $msg['username'];
            $connection->send(json_encode(['type' => 'ok', 'message' => 'Регистрация успешна']));
            break;

        case 'find_new_game':
            // Поиск соперника
            if (!empty($waitingPlayers)) {
                // Находим другого игрока
                $opponent = null;
                foreach ($waitingPlayers as $key => $wp) {
                    if ($wp !== $connection) {
                        $opponent = $wp;
                        unset($waitingPlayers[$key]);
                        break;
                    }
                }

                if ($opponent) {
                    $roomCounter++;
                    $roomId = $roomCounter;

                    $rooms[$roomId] = [
                        'players' => [$connection, $opponent],
                        'game' => null
                    ];

                    $connection->roomId = $roomId;
                    $opponent->roomId = $roomId;

                    // Отправляем комнату
                    $connection->send(json_encode(['type' => 'room_assigned', 'roomId' => $roomId]));
                    $opponent->send(json_encode(['type' => 'room_assigned', 'roomId' => $roomId]));

                    // Запускаем игру
                    $connection->send(json_encode([
                        'type' => 'game_start',
                        'yourColor' => 'white',
                        'opponent' => ['username' => $opponent->username]
                    ]));

                    $opponent->send(json_encode([
                        'type' => 'game_start',
                        'yourColor' => 'black',
                        'opponent' => ['username' => $connection->username]
                    ]));

                    echo "🎮 Игра началась в комнате {$roomId}\n";
                    break;
                }
            }

            // Если соперника нет - в очередь
            $waitingPlayers[] = $connection;
            $connection->send(json_encode(['type' => 'waiting', 'message' => 'Поиск соперника...']));
            break;

        case 'move':
            if ($connection->roomId && isset($rooms[$connection->roomId])) {
                $room = $rooms[$connection->roomId];
                foreach ($room['players'] as $player) {
                    if ($player !== $connection) {
                        $player->send(json_encode([
                            'type' => 'move',
                            'move' => $msg['move'],
                            'fen' => $msg['fen']
                        ]));
                    }
                }
            }
            break;

        case 'chat':
            if ($connection->roomId && isset($rooms[$connection->roomId])) {
                $room = $rooms[$connection->roomId];
                foreach ($room['players'] as $player) {
                    if ($player !== $connection) {
                        $player->send(json_encode([
                            'type' => 'chat',
                            'message' => $msg['message'],
                            'username' => $connection->username
                        ]));
                    }
                }
            }
            break;

        case 'ping':
            $connection->send(json_encode(['type' => 'pong']));
            break;
    }
};

// При отключении
$worker->onClose = function($connection) use (&$rooms, &$waitingPlayers) {
    // Удаляем из очереди ожидания
    foreach ($waitingPlayers as $key => $player) {
        if ($player === $connection) {
            unset($waitingPlayers[$key]);
            break;
        }
    }

    // Удаляем из комнаты
    if ($connection->roomId && isset($rooms[$connection->roomId])) {
        $room = $rooms[$connection->roomId];
        foreach ($room['players'] as $player) {
            if ($player !== $connection) {
                $player->send(json_encode(['type' => 'opponent_left']));
                $player->roomId = null;
            }
        }
        unset($rooms[$connection->roomId]);
    }

    echo "❌ Клиент отключился\n";
};

// Запускаем
Worker::runAll();