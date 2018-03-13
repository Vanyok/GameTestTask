<?php

/* @var $this yii\web\View */

$this->title = 'Case Game Example';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You can win a prize!!! Just puss the button!!!</p>

        <p><a id="start_butt" class="btn btn-lg btn-warning" href="javascript: void(0);" onclick="game.startGame()">Get
                prize!</a></p>
        <div class="progress" style="display: none;">
            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                 aria-valuemax="100" style="width: 60%"><span class="sr-only">60% Complete</span></div>
        </div>
    </div>


    <div class="body-content">
        <div class="row text-center">
            <div class="col-lg-12 text-center">
                <h2 style="display: none" class="ico item cash">You win!!!</h2>
                <h5 id="winner_text"></h5>
                <a style="display: none" class="btn btn-default ico item cash" href="javascript: void(0);"
                   onclick="game.get_prize()">Get it</a>
                <a style="display: none" class="btn btn-default cash " href="javascript: void(0);"
                   onclick="game.convert_prize()">Convert to ICO</a>
                <a style="display: none" class="btn btn-default ico item cash" href="javascript: void(0);"
                   onclick="game.cancel_prize()">I don't need your prize</a>
            </div>

        </div>

    </div>

</div>
