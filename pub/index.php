<?php
require("./../src/config.php");

use Steampixel\Route;

Route::add('/' , function() {
    global $twig;
    $posts = Post::getPage();
    $t = array("posts" => $posts);
    $twig->display("index.html.twig", $t);
});

Route::add('/upload' , function() {
    global $twig;
    $twig->display("upload.html.twig");
});

Route::add('/upload', function() {
    //ta funkcja się uruchamia po wysłaniu formularza
    global $twig;

    $tempFileName = $_FILES['uploadedFile']['tmp_name'];
    $title = $_POST['title'];
    Post::upload($tempFileName, $title);

    //na koniec wyświetlaj główną stronę
    $twig->display("index.html.twig");
}, 'post');

Route::run('/cms/pub');

?>