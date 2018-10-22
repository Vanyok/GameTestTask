var Game = function (params) {

    var prize_id = -1;
    var user_id = params.user_id;
    var start_url = params.start_url;
    var cancel_url = params.cancel_url;
    var get_url = params.get_url;
    var convert_url = params.convert_url;
    var winnerTExtEl = $("#winner_text");
    var startGame = function () {
        if (user_id === -1) {
            alert('You should register or login first!');
            return;
        }
        loading_start();
        hide_butt();

        $.ajax({
            url: start_url,
            type: "POST",
            dataType: 'JSON',
            success: function (data) {
                if (data.status === 'success') {
                    var html = '';
                    if (data.prize_type === 'cash') {
                        html = "Congrats!!!, You've win " + data.prize_amount + "$. You can withdraw them to your banc account or convert to ICO";
                        $('.cash').show();
                    } else if (data.prize_type === 'item') {
                        html = "Congrats!!!, You've win " + data.prize_description + ". You can get it by post";
                        $('.item').show();
                    } else if (data.prize_type === 'ico') {
                        html = "Congrats!!!, You've win " + data.prize_amount + " ICO. You can add them to your account";
                        $('.ico').show();
                    }
                    winnerTExtEl.html(html);
                    prize_id = data.prize_id;
                } else {
                    alert('Pls try later');
                    show_butt();
                }
                loading_stop();
            },
            error: function () {
                alert('Pls try later');
                loading_stop();
                show_butt();
            }

        })
    };


    var cancel_prize = function () {
        if (user_id === -1) {
            alert('You should register or login first!');
            return;
        }
        loading_start();

        $.ajax({
            url: cancel_url,
            dataType: 'JSON',
            type: "POST",
            data: {
                prize_id: prize_id
            },
            success: function (data) {
                if (data.status === 'success') {
                    alert('Your prize was cancelled');
                    prize_id = -1;
                    clearWinnerText()
                } else {
                    alert('Pls try later');
                }
                loading_stop();

            },
            error: function () {
                alert('Pls try later');
                loading_stop();
            }

        })
    };
    var get_prize = function () {
        if (user_id === -1) {
            alert('You should register or login first!');
            return;
        }
        loading_start();
        $.ajax({
            url: get_url,
            dataType: 'JSON',
            type: "POST",
            data: {
                prize_id: prize_id
            },
            success: function (data) {
                if (data.status === 'success') {
                    alert('Your prize will sent in 3 days');
                    prize_id = -1;
                    clearWinnerText()
                } else {
                    alert('Pls try later');
                }
                loading_stop();

            },
            error: function () {
                alert('Pls try later');
                loading_stop();
            }

        })
    };
    var convert_prize = function () {
        if (user_id === -1) {
            alert('You should register or login first!');
            return;
        }
        loading_start();
        $.ajax({
            url: convert_url,
            dataType: 'JSON',
            type: "POST",
            data: {
                prize_id: prize_id
            },
            success: function (data) {
                if (data.status ==='success') {
                    alert('Your prize was converted to ICO');
                    prize_id = -1;
                    clearWinnerText()
                } else {
                    alert('Pls try later');
                }
                loading_stop();
            },
            error: function () {
                alert('Pls try later');
                loading_stop();
            }

        })
    };

    function clearWinnerText() {
        winnerTExtEl.html("");
        $(".cash").hide();
        show_butt();
    }

    function loading_start() {
        $('#progress').show();
    }

    function loading_stop() {
        $('#progress').hide();
    }

    function hide_butt() {
        $('#start_butt').hide();
    }

    function show_butt() {
        $('#start_butt').show();
    }


    return {
        startGame: startGame,
        cancel_prize: cancel_prize,
        get_prize: get_prize,
        convert_prize: convert_prize
    }
};

