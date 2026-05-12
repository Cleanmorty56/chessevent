<?php use yii\helpers\Url;?>
<div class="admin-default-index chess-admin-theme">
    <h1 class="chess-admin-title">Административная панель</h1>
    <p class="chess-admin-subtitle">Управление шахматной организацией</p>

    <div class="chess-divider"></div>

    <div class="chess-button-container">
        <a href="<?php echo Url::to(['tournament/index']) ?>" class="chess-admin-btn chess-tournament-btn">
            <i class="fas fa-chess-board"></i>
            <span>Управление турнирами</span>
        </a>

        <a href="<?php echo Url::to(['planning/index']) ?>" class="chess-admin-btn chess-planning-btn">
            <i class="fas fa-chess-king"></i>
            <span>Заявки на планирование турниров</span>
        </a>
    </div>

    <div class="chess-board-decoration">
        <div class="chess-piece king"></div>
        <div class="chess-piece queen"></div>
        <div class="chess-piece rook"></div>
    </div>
</div>

<style>
    main {
        display: flex;
        align-items: center;
    }
</style>