<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cas Game Example</title>
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/cas_game.js"></script>
</head>
<body>

<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You can win a prize!!! Just puss the button!!!</p>

        <p><a id="start_butt" class="btn btn-lg btn-warning" href="javascript: void(0);" onclick="game.startGame()">Get
                prize!</a></p>

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
<script type="text/javascript">
    var game;
    $(document).ready(function ($) {
        var params = {
            user_id: 1,
            start_url: "?a=start_game",
            cancel_url: "?a=cancel_prize",
            get_url: "?a=get_prize",
            convert_url: "?a=convert_prize"
        };
        game = new Game(params);
    });

</script>
</body>
</html>