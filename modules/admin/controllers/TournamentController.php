<?php

namespace app\modules\admin\controllers;

use app\models\Gamemode;
use app\models\Level;
use app\models\RegToTournament;
use app\models\Tournament;
use app\models\TournamentBye;
use app\models\TournamentMatch;
use app\models\TournamentSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TournamentController implements the CRUD actions for Tournament model.
 */
class TournamentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Tournament models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tournament model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tournament model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $items = Gamemode::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();

        $levels = Level::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();


        $model = new Tournament();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->img = UploadedFile::getInstance($model, 'img');

                if ($model->validate() && $model->save()) {
                    if ($model->img && $model->upload()) {
                        Yii::$app->session->setFlash('success', 'Турнир создан и изображение загружено');
                    } else {
                        Yii::$app->session->setFlash('success', 'Турнир создан');
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'items' => $items,
            'levels' => $levels,
        ]);
    }

    /**
     * Updates an existing Tournament model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Загружаем списки для выпадающих списков
        $items = Gamemode::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();

        $levels = Level::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Обработка загрузки изображения
            $model->img = UploadedFile::getInstance($model, 'img');

            if ($model->save()) {
                if ($model->img && $model->upload()) {
                    Yii::$app->session->setFlash('success', 'Турнир обновлен, изображение загружено');
                } else {
                    Yii::$app->session->setFlash('success', 'Турнир обновлен');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items,
            'levels' => $levels,
        ]);
    }

    /**
     * Deletes an existing Tournament model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tournament model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tournament the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tournament::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Управление турниром (жеребьевка, результаты)
     */
    public function actionManage($id)
    {
        $model = $this->findModel($id);

        // Получаем участников из reg_to_tournament
        $participants = RegToTournament::find()
            ->where(['tournament_id' => $id])
            ->with('user')
            ->all();

        // Сортируем по ELO
        usort($participants, function ($a, $b) {
            $eloA = $a->user->elo ?? 1200;
            $eloB = $b->user->elo ?? 1200;
            return $eloB - $eloA;
        });

        // Получаем партии
        $matches = TournamentMatch::find()
            ->where(['tournament_id' => $id])
            ->with(['whitePlayer', 'blackPlayer'])
            ->orderBy(['round' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        // Группируем по турам
        $rounds = [];
        foreach ($matches as $match) {
            $rounds[$match->round][] = $match;
        }

        // Считаем несыгранные партии
        $pendingMatches = 0;
        foreach ($matches as $match) {
            if (!$match->isPlayed()) {
                $pendingMatches++;
            }
        }

        return $this->render('manage', [
            'model' => $model,
            'participants' => $participants,
            'rounds' => $rounds,
            'pendingMatches' => $pendingMatches,
            'hasDraw' => !empty($matches),
        ]);
    }

    /**
     * Жеребьевка турнира
     */
    public function actionDraw($id)
    {
        $model = $this->findModel($id);

        // Проверка статуса
        if ($model->status != 'Запланирован' && $model->status != 'В процессе') {
            Yii::$app->session->setFlash('error', 'Жеребьевка возможна только для запланированного или активного турнира.');
            return $this->redirect(['manage', 'id' => $id]);
        }

        // Определяем следующий тур
        $lastRound = TournamentMatch::find()
            ->where(['tournament_id' => $id])
            ->max('round');

        $nextRound = $lastRound ? $lastRound + 1 : 1;

        // Проверка на максимальное количество туров
        if ($nextRound > $model->quantity_rounds) {
            Yii::$app->session->setFlash('error', 'Достигнуто максимальное количество туров.');
            return $this->redirect(['manage', 'id' => $id]);
        }

        // Проверка, все ли партии прошлого тура сыграны
        if ($lastRound) {
            $pending = TournamentMatch::find()
                ->where(['tournament_id' => $id, 'round' => $lastRound, 'status' => 'pending'])
                ->exists();

            if ($pending) {
                Yii::$app->session->setFlash('error', 'Не все партии предыдущего тура сыграны.');
                return $this->redirect(['manage', 'id' => $id]);
            }
        }

        // Получаем участников
        $registrations = RegToTournament::find()
            ->where(['tournament_id' => $id])
            ->with('user')
            ->all();

        if (count($registrations) < 2) {
            Yii::$app->session->setFlash('error', 'Недостаточно участников (минимум 2).');
            return $this->redirect(['manage', 'id' => $id]);
        }

        // Сортируем по ELO
        $players = [];
        foreach ($registrations as $reg) {
            $players[] = [
                'user_id' => $reg->user_id,
                'username' => $reg->user->username,
                'elo' => $reg->user->elo ?? 1200,
            ];
        }

        usort($players, function ($a, $b) {
            return $b['elo'] - $a['elo'];
        });

        // Делим на верхнюю и нижнюю половины
        $half = (int)ceil(count($players) / 2);
        $topHalf = array_slice($players, 0, $half);
        $bottomHalf = array_slice($players, $half);

        // Создаем пары
        $pairs = [];
        $pairCount = min(count($topHalf), count($bottomHalf));

        for ($i = 0; $i < $pairCount; $i++) {
            // Случайно выбираем цвета
            $whiteFirst = rand(0, 1) == 1;

            $pairs[] = [
                'white' => $whiteFirst ? $topHalf[$i]['user_id'] : $bottomHalf[$i]['user_id'],
                'black' => $whiteFirst ? $bottomHalf[$i]['user_id'] : $topHalf[$i]['user_id'],
            ];
        }

        // Сохраняем в базу
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($pairs as $pair) {
                $match = new TournamentMatch();
                $match->tournament_id = $id;
                $match->round = $nextRound;
                $match->white_player_id = $pair['white'];
                $match->black_player_id = $pair['black'];
                $match->result = 'pending';
                $match->status = 'pending';
                $match->save();
            }

            // Обновляем статус турнира
            if ($nextRound == 1) {
                $model->status = 'В процессе';
                $model->save();
            }

            $transaction->commit();

            Yii::$app->session->setFlash('success', "Жеребьевка {$nextRound}-го тура проведена. Создано " . count($pairs) . " партий.");

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ошибка: ' . $e->getMessage());
        }

        return $this->redirect(['manage', 'id' => $id]);
    }

    /**
     * Обновление результата партии
     */
    public function actionUpdateMatch($id)
    {
        $match = TournamentMatch::findOne($id);

        if (!$match) {
            throw new NotFoundHttpException('Партия не найдена.');
        }

        if (Yii::$app->request->isPost) {
            $result = Yii::$app->request->post('result');

            if (!in_array($result, ['white_win', 'black_win', 'draw'])) {
                Yii::$app->session->setFlash('error', 'Неверный результат.');
                return $this->redirect(['manage', 'id' => $match->tournament_id]);
            }

            $match->result = $result;
            $match->status = 'played';
            $match->played_at = date('Y-m-d H:i:s');

            if ($result == 'white_win') {
                $match->winner_id = $match->white_player_id;
            } elseif ($result == 'black_win') {
                $match->winner_id = $match->black_player_id;
            } else {
                $match->winner_id = null;
            }

            if ($match->save()) {
                Yii::$app->session->setFlash('success', 'Результат сохранен.');

                // Проверяем, завершен ли турнир
                $tournament = Tournament::findOne($match->tournament_id);
                $pendingExists = TournamentMatch::find()
                    ->where(['tournament_id' => $match->tournament_id, 'status' => 'pending'])
                    ->exists();

                $currentRound = TournamentMatch::find()
                    ->where(['tournament_id' => $match->tournament_id])
                    ->max('round');

                if (!$pendingExists && $currentRound >= $tournament->quantity_rounds) {
                    $tournament->status = 'Завершен';
                    $tournament->save();
                    Yii::$app->session->setFlash('success', 'Турнир завершен!');
                }
            }
        }

        return $this->redirect(['manage', 'id' => $match->tournament_id]);
    }

    /**
     * Сброс жеребьевки
     */
    public function actionResetDraw($id)
    {
        $model = $this->findModel($id);

        if ($model->status == 'Завершен') {
            Yii::$app->session->setFlash('error', 'Нельзя сбросить завершенный турнир.');
            return $this->redirect(['manage', 'id' => $id]);
        }

        TournamentMatch::deleteAll(['tournament_id' => $id]);
        TournamentBye::deleteAll(['tournament_id' => $id]);

        $model->status = 'Запланирован';
        $model->save();

        Yii::$app->session->setFlash('success', 'Жеребьевка сброшена.');

        return $this->redirect(['manage', 'id' => $id]);
    }


}
