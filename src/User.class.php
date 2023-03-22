<?php
class User {
    private $email;


    public function __construct($email) {
        $this->email = $email;

    }

    //gettery
    public function getName() : string {
        return $this->email;
    }

    public static function register(string $email, string $password) : bool {
        global $db;
        $query = $db->prepare("INSERT INTO user VALUES (NULL, ?, ?)");
        $passwordHash = password_hash($password, PASSWORD_ARGON2I);
        $query->bind_param('ss', $email, $passwordHash);
        return $query->execute();
    }

    public static function login(string $email, string $password) {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $passwordHash = $row['password'];
        if(password_verify($password, $passwordHash)) {
            //hasła są zgodne - możemy zalogować użytkownika
            $u = new User($email);
            $_SESSION['user'] = $u;
        }
    }
}

?>