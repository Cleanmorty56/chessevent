<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Gamemode;
use app\models\Level;
use app\models\LoginForm;
use app\models\Planning;
use app\models\RegToTournament;
use app\models\Signup;
use app\models\Tournament;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $items = Gamemode::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();

        $model = new Planning();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Для отправки заявки необходимо авторизоваться');
                return $this->redirect(['site/login']);
            }

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->save()) {
                if ($model->upload()) {
                    Yii::$app->session->setFlash('success', 'Загружено');
                    return $this->refresh();
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->identity->role === 1) {
                return $this->redirect(['/admin']);
            }
            return $this->redirect(['/profile/profile']);
        }

        $model->password = "";
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->goHome();
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }

    public function actionTournaments($id = null)
    {
        $levels = Level::find()->all();

        $query = Tournament::find();

        if ($id !== null && $id !== 'all') {
            $query->where(['level_id' => $id]);
            $selectedLevel = Level::findOne($id);
        } else {
            $selectedLevel = null;
        }

        $tournaments = $query->all();
        $userTournamentIds = [];

        if (!Yii::$app->user->isGuest) {
            $userTournamentIds = Yii::$app->user->identity->getRegToTournaments()
                ->select('tournament_id')
                ->column();
        }

        return $this->render('tournaments', [
            'levels' => $levels,
            'tournaments' => $tournaments,
            'userTournamentIds' => $userTournamentIds,
            'selectedLevel' => $selectedLevel,
            'selectedLevelId' => $id,
        ]);
    }

    public function actionRegisterForTournament($tournamentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $tournament = Tournament::findOne($tournamentId);

        if (!$tournament || !$tournament->isAvailableForRegistration()) {
            Yii::$app->session->setFlash('error', 'Этот турнир уже начался или завершен, регистрация невозможна.');
            return $this->redirect(Yii::$app->request->referrer ?: ['site/tournament']);
        }

        $userId = Yii::$app->user->id;
        $existingRegistration = RegToTournament::find()
            ->where(['user_id' => $userId, 'tournament_id' => $tournamentId])
            ->exists();

        if ($existingRegistration) {
            Yii::$app->session->setFlash('warning', 'Вы уже зарегистрированы на этот турнир.');
            return $this->redirect(Yii::$app->request->referrer ?: ['site/tournament']);
        }

        $registration = new RegToTournament([
            'user_id' => $userId,
            'tournament_id' => $tournamentId,
        ]);

        if (!$registration->save()) {
            Yii::$app->session->setFlash('error', 'Произошла ошибка при регистрации.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['site/tournament']);
    }
}
