<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\Prize;
use app\models\UserAccount;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
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
        return $this->render('index');
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
            return $this->goBack();
        }
        $model->password = '';
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
     * Cancel prize ajax action
     */

    public function actionCancel_prize()
    {
        $prize = $this->loadPrize();
        if (isset($prize)) {
            $prize->delete();
        }
        return json_encode(['status' => 'success',]);
    }

    private function loadPrize()
    {
        $prize = null;
        if (isset($_POST['prize_id'])) {
            $prize = Prize::findOne($_POST['prize_id']);
        }
        return $prize;
    }

    /**
     * Get prize ajax action
     */

    public function actionGet_prize()
    {
        $prize = $this->loadPrize();
        if (isset($prize)) {
            if ($prize->type == Prize::PRIZE_ICO) {
                $prize->status = Prize::PRIZE_DELIVERED;
                $prize->save(false);
                UserAccount::addIcoToUserAccount($prize->user_id, $prize->amount);
            } else {
                $prize->status = Prize::PRIZE_DELIVERY_SHEDULED;
                $prize->save(false);
            }
            return json_encode(['status' => 'success']);
        }
        return json_encode(['status' => 'fail']);
    }

    /**
     * Send prize action
     */

    public function actionSend_prize()
    {
        $prize = $this->loadPrize();
        if (isset($prize)) {
            if ($prize->type == Prize::PRIZE_CASH) {
                $prize->sendCash();
                $this->redirect(['site/manage_cash']);
            } elseif ($prize->type == Prize::PRIZE_ITEM) {
                $prize->sendItem();
                $this->redirect(['site/manage_items']);
            }
        }
        $this->redirect(['site/error']);
    }

    /**
     * Convert prize ajax action
     */

    public function actionConvert_to_ico()
    {
        $prize = $this->loadPrize();
        if (isset($prize)) {
            UserAccount::convertCashToIco($prize->user_id, $prize->amount);
            $prize->delete(); // release cash for next games
        }
        return json_encode(['status' => 'success',]);
    }

    /**
     * manage cash prizes
     */
    public function actionManage_cash()
    {
        $model = new Prize();
        if (isset($_POST['Prize'])) {
            $model->load($_POST);
        }
        return $this->render('manage_cash',
            ['model' => $model]);
    }

    /**
     * manage item prizes
     */

    public function actionManage_items()
    {
        $model = new Prize();
        if (isset($_POST['Prize'])) {
            $model->load($_POST);
        }
        return $this->render('manage_items',
            ['model' => $model]);
    }

    /**
     * Hold Bank callback
     */

    public function actionCallback()
    {
        $prize = $this->loadPrize();
        if (isset($prize)) {
            if ($_POST['status'] == '2') {
                $prize->status = Prize::PRIZE_DELIVERED;
            } else {
                $prize->status = Prize::PRIZE_DELIVERY_SHEDULED;
            }
            $prize->save(false);
        }
        Yii::$app->end();
    }

    /**
     * Start game ajax action
     */

    public function actionStart_game()
    {
        $prize = (new Prize())->runGame();
        if (isset($prize)) {
            $responce = [
                'status' => 'success',
                'prize_type' => $prize->type,
                'prize_amount' => $prize->amount,
                'prize_id' => $prize->id,
                'prize_description' => $prize->description
            ];
        } else {
            $responce = ['status' => false];
        }
        return json_encode($responce);
    }
}
