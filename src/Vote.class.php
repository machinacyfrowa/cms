<?php
class Vote {
    /*
    Deklarujemy sobie w bazie tabelę, 3 kolumny - id, value, user_id gdzie:
    id - int autoincrement
    value - int (-1,1)
    user_id - int, klucz obcy do user.id
    */

    public static function upVote(int $postId, int $userId) : bool {
        //kod do dodawania upvotów
        global $db;
        $query = $db->prepare("INSERT INTO vote VALUES (NULL, 1, ?)");
        $query->bind_param('i', $userId);
        if($query->execute())
            return true;
        return false;
    }
    public static function downVote(int $postId, int $userId) : bool {
        global $db;
        $query = $db->prepare("INSERT INTO vote VALUES (NULL, -1, ?)");
        $query->bind_param('i', $userId);
        if($query->execute())
            return true;
        return false;
    }
    public static function getScore(int $postId) : int {
        //zwróć sumę głosów dla danaego posta


        //tymczasowo
        return 0;
    }
    
}
?>