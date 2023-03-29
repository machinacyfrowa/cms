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

    //na koniec wyświetlaj główną stronę - przekierowanie
    header("Location: //" . $_SERVER['HTTP_HOST'] ."/cms/pub/");
}, 'post');

Route::add('/register', function() {
    global $twig;
    $twigData = array("pageTitle" => "Zarejestruj użytkownika");
    $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function(){
    global $twig;
    if(isset($_POST['submit'])) {
        User::register($_POST['email'], $_POST['password']);
        header("Location: http://localhost/cms/pub");
    }
}, 'post');

Route::add('/login', function(){
    global $twig;
    $twigData = array("pageTitle" => "Zaloguj użytkownika");
    $twig->display("login.html.twig", $twigData);
});

Route::add('/login', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        User::login($_POST['email'], $_POST['password']);
    }
    header("Location: http://localhost/cms/pub");

}, 'post');


Route::run('/cms/pub');

?>