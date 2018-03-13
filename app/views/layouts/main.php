<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
$this->registerJsFile('@web/js/cas_game.js');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            !Yii::$app->user->isGuest ? (
            ['label' => 'balance ' . \app\models\UserAccount::getAccountInf(Yii::$app->user->id) . ' ICO']
            ) : '',
            ['label' => 'Game', 'url' => ['/site/index']],
            ['label' => 'Profile', 'url' => ['/user/profile/show']],
            ['label' => "Manage cash Prizes", 'url' => ['site/manage_cash']],
            ['label' => "Manage item Prizes", 'url' => ['site/manage_items']],
            Yii::$app->user->isGuest ? (
            ['label' => 'Register', 'url' => ['/user/registration/register']]
            ) : '',
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/user/security/login']]

            ) : (
                '<li>'
                . Html::beginForm(['/user/security/logout '], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Ivan.K <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<script type="text/javascript">
    var game;
    $(document).ready(function ($) {
        var params = {
            user_id: <?= Yii::$app->user->isGuest ? -1 : Yii::$app->user->id ?>,
            start_url: "<?= Url::to(['site/start_game'])?>",
            cancel_url: "<?= Url::to(['site/cancel_prize'])?>",
            get_url: "<?= Url::to(['site/get_prize'])?>",
            convert_url: "<?= Url::to(['site/convert_to_ico'])?>"
        };
        game = new Game(params);
    });

</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
