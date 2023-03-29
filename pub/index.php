<?php
require("./../src/config.php");
session_start();
use Steampixel\Route;

Route::add('/', function() {
    //strona wyświetlająca obrazki
    global $twig;
    //pobierz 10 najnowszych postów
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray,
                        "pageTitle" => "Strona główna",
                        );
    //jeśli użytkownik jest zalogowany to przekaż go do twiga
    if(isset($_SESSION['user']))
        $twigData['user'] = $_SESSION['user'];
    $twig->display("index.html.twig", $twigData);
});

Route::add('/upload', function() {
    //strona z formularzem do wgrywania obrazków
    global $twig;
    $twigData = array("pageTitle" => "Wgraj mema");
    //jeśli użytkownik jest zalogowany to przekaż go do twiga
    if(User::isAuth()) {
        $twigData['user'] = $_SESSION['user'];
        $twig->display("upload.html.twig", $twigData);
    }
    else {
        //zwróć kod 403 czyli zabronione
        http_response_code(403);
    }    
});

Route::add('/upload', function() {
    //wywoła się tylko po otrzymaniu danych metodą post na ten url
    // (po wypełnieniu formularza)
    global $twig;
    if(isset($_POST['submit']))  {
        Post::upload($_FILES['uploadedFile']['tmp_name'], $_POST['title'], $_POST['userId']);
    }
    //TODO: zmienić na ścieżkę względną
    header("Location: http://localhost/cms/pub");
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
        if(User::login($_POST['email'], $_POST['password'])) {
            //jeśli zalogowano poprawnie to wyświetl główną stronę
            header("Location: http://localhost/cms/pub");
        } else {
            //jeśli nie zalogowano poprawnie wyświetl ponownie stronę logowania z komunikatem
            $twigData = array("pageTitle" => "Zaloguj użytkownika",
                                "message" => "Niepoprawny użytkownik lub hasło");
            $twig->display("login.html.twig", $twigData);
        }
    }
    

}, 'post');

Route::add('/admin', function() {
    global $twig;
    if(User::isAuth()) {
        $postsList = Post::getPage(1, 100);
        $twigData = array("postList" => $postsList);
        $twig->display("admin.html.twig", $twigData);
    } else {
        http_response_code(403);
    }
});

Route::add('/admin/remove/([0-9]*)', function($id) {
    if(User::isAuth()) {
        Post::remove($id);
        header("Location: http://localhost/cms/pub/admin");
    } else {
        http_response_code(403);
    }
});

Route::run('/cms/pub');

?>