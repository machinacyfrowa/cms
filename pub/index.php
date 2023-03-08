<?php
require("./../src/config.php");

use Steampixel\Route;

Route::add('/' , function() {
    global $twig;
    $twig->display("index.html.twig");
});

Route::add('/upload' , function() {
    global $twig;
    $twig->display("upload.html.twig");
});

Route::run('/cms/pub');

?>