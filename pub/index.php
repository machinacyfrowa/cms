<?php
require_once('./../src/config.php');

use Steampixel\Route;

Route::add('/', function() {
    //strona wyświetlająca obrazki
    global $twig;
    $twig->display("index.html.twig");
});

Route::add('/upload', function() {
    //strona z formularzem do wgrywania obrazków
    global $twig;
    $twig->display("upload.html.twig");
});

Route::run('/cms/pub');
?>