<?php
/** @var yii\web\View $this */

use yii\helpers\Html;

$this->registerCssFile("@web/css/onas.css");
$this->title = 'О нас';
?>

<div class="about-page">
    <div class="chess-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <h5>Страсть к игре, совершенствование мастерства, сообщество единомышленников</h5>
    </div>

    <div class="about-container">
        <section class="mission-section">
            <h2>Наша миссия</h2>
            <p style="font-size: large">Мы создали эту организацию в 2024 году с целью объединить любителей шахмат всех
                уровней. Наша миссия -
                популяризация шахмат как интеллектуального спорта, развитие стратегического мышления и создание
                дружелюбного сообщества.</p>
        </section>

        <section class="team-section">
            <h2>Наша команда</h2>
            <div class="team-grid">
                <div class="team-member">
                    <?= Html::img('@web/img/vladimir.jpg', ['alt' => 'Владимир Петров', 'class' => 'member-photo']) ?>
                    <div class="member-info">
                        <h3>Владимир Петров</h3>
                        <p>Главный тренер, гроссмейстер</p>
                    </div>
                </div>
                <div class="team-member">
                    <?= Html::img('@web/img/ww.jpg', ['alt' => 'Дмитрий Лакаев', 'class' => 'member-photo']) ?>
                    <div class="member-info">
                        <h3>Дмитрий Лакаев</h3>
                        <p>Организатор турниров</p>
                    </div>
                </div>
                <div class="team-member">
                    <?= Html::img('@web/img/tactic.jpg', ['alt' => 'Иван Иванов', 'class' => 'member-photo']) ?>
                    <div class="member-info">
                        <h3>Иван Иванов</h3>
                        <p>Тренер по тактике</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="join-section">
            <h2>Присоединяйтесь к нам</h2>
            <p style="font-size: large">Мы всегда рады новым участникам! Независимо от вашего уровня, у нас найдется
                место для вас. Участвуйте в турнирах, посещайте мастер-классы или просто приходите сыграть партию.</p>
            <?php if (Yii::$app->user->isGuest):?>
                <?= Html::a('Зарегистрироваться', ['site/signup'], ['class' => 'btn btn-chess']) ?>
            <?php endif;?>
        </section>
    </div>
</div>