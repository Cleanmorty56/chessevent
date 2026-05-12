<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerCssFile('https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <style>
        .navbar-brand {
            width: 256px;
            display: flex;
            justify-content: center;
        }

        .social-icons {
            display: flex;
            width: 204px;
            gap: 10px;
        }
    </style>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Html::img('@web/img/logo.png', ['alt' => 'My logo', 'style' => 'width: 100px']),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark',
                'style' => 'background-color: #6b5534 !important;'
            ]
        ]);
        ?>
            <div class="navbar-nav mx-auto">
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav'],
                    'items' => [
                        ['label' => 'Главная', 'url' => ['/site/index']],
                        ['label' => 'Турниры', 'url' => ['/site/tournaments']],
                        ['label' => 'О нас', 'url' => ['/site/about']],
                        ['label' => 'Шахматы онлайн', 'url' => ['/chess']],
                        ['label' => 'Админ-панель', 'url' => ['/admin/'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 1],
                        ['label' => 'Профиль', 'url' => ['/profile/profile'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 0],
                        ['label' => 'Регистрация', 'url' => ['/site/signup'], 'visible' => Yii::$app->user->isGuest],
                    ]
                ]);
                ?>
            </div>

            <!-- Правая часть -->
            <div class="d-flex align-items-center gap-3">
                <div class="navbar-nav">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <a class="nav-link" href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">Вход</a>
                    <?php else: ?>
                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex align-items-center']) ?>
                        <?= Html::submitButton(
                            'Выход',
                            ['class' => 'nav-link btn btn-link logout']
                        ) ?>
                        <?= Html::endForm() ?>
                    <?php endif; ?>
                </div>

                <!-- Соц сети -->
                <div class="social-icons">
                    <a href="#"
                       class="social-icon"><?= Html::img('@web/img/free-icon-vk-2504953.png', ['alt' => 'VK', 'style' => 'width: 24px; height:24px;']) ?></a>
                    <a href="#"
                       class="social-icon"><?= Html::img('@web/img/free-icon-telegram-2111646.png', ['alt' => 'Telegram', 'style' => 'width: 24px; height:24px;']) ?></a>
                    <a href="#"
                       class="social-icon"><?= Html::img('@web/img/free-icon-odnoklassniki-2504930.png', ['alt' => 'OK', 'style' => 'width: 24px; height:24px;']) ?></a>
                </div>
            </div>

            <!-- Кнопка для мобильного меню -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <?php NavBar::end(); ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container" style="padding: 0; !important;">
            <div><?= $content ?></div>
        </div>
    </main>

    <footer id="footer" class="py-4" style="background-color: #6b5534;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start">
                    <?php echo Html::img('@web/img/logo.png', ['alt' => 'ChessEvent', 'style' => 'width: 128px; height: 128px;']); ?>
                </div>
                <div class="col-md-4">
                    <nav class="footer-nav d-flex justify-content-center flex-wrap">
                        <?php
                        $menuItems = [
                            ['label' => 'Главная', 'url' => ['/site/index']],
                            ['label' => 'Турниры', 'url' => ['/site/level']],
                            ['label' => 'О нас', 'url' => ['/site/about']],
                        ];
                        if (Yii::$app->user->isGuest) {
                            $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
                        } else {
                            if (Yii::$app->user->identity->role === 1) {
                                $menuItems[] = ['label' => 'Админ-панель', 'url' => ['/admin']];
                            } else {
                                $menuItems[] = ['label' => 'Профиль', 'url' => ['/profile/profile']];
                            }
                        }
                        foreach ($menuItems as $item) {
                            echo Html::a($item['label'], $item['url'], ['class' => 'nav-link mx-2 text-white']);
                        }
                        ?>
                    </nav>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="social-icons">
                        <a href="#"
                           class="social-icon"><?= Html::img('@web/img/free-icon-vk-2504953.png', ['alt' => 'VK', 'style' => 'width: 24px; height:24px;']) ?></a>
                        <a href="#"
                           class="social-icon"><?= Html::img('@web/img/free-icon-telegram-2111646.png', ['alt' => 'Telegram', 'style' => 'width: 24px; height:24px;']) ?></a>
                        <a href="#"
                           class="social-icon"><?= Html::img('@web/img/free-icon-odnoklassniki-2504930.png', ['alt' => 'OK', 'style' => 'width: 24px; height:24px;']) ?></a>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="footer-copyright text-white">
                        &copy; ChessEvent <?= date('Y') ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>