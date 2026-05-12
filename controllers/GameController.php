<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class GameController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Только для авторизованных
                    ],
                ],
            ],
        ];
    }

    /**
     * Главная страница быстрой игры
     */
    public function actionQuickPlay()
    {
        // Получаем данные текущего пользователя
        $user = Yii::$app->user->identity;

        // Определяем WebSocket URL в зависимости от окружения
        $isLocalhost = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

        $wsUrl = $isLocalhost ? 'ws://localhost:8085' : 'wss://' . $_SERVER['HTTP_HOST'] . '/ws';

        return $this->render('quick-play', [
            'userId' => Yii::$app->user->id,
            'user' => $user,
            'wsUrl' => $wsUrl
        ]);
    }

    /**
     * Страница помощи/правил
     */
    public function actionHelp()
    {
        return $this->render('help');
    }
}