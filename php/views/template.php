<?php
/**
 * @var $content_view
 */
if(file_exists('views/' . $content_view . '.php')){
    ?>
    <div id="content">
    <div class="box">
        <?php include 'views/' . $content_view . '.php'; ?>
    </div>
    </div>
<?
}else{
    ?>
    <div id="content">
        <div class="box">
            <h3>Error 404!</h3>
        </div>
    </div>
<?php
}
?>

