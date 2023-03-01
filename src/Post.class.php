<?php
class Post {
    static function upload(string $tempFileName) {
        //funkcja działa bez tworzenia instancji obiektu
        // uwaga wywołanie metodą Post::upload()
        $uploadDir = "img/";
        //sprawdź czy mamy do czynienia z obrazem
        $imgInfo = getimagesize($tempFileName);
        //jeśli plik nie jest poprawnym obrazem
        if(!is_array($imgInfo)) {
            die("BŁĄD: Przekazany plik nie jest obrazem!");
        }
        //wygeneruj _możliwie_ losowy ciąg liczbowy
        $randomSeed = rand(10000,99999) . hrtime(true);
        //wygeneruj hash, który będzie nową nazwą pliku
        $hash = hash("sha256", $randomSeed);
        //wygeneruj kompletną nazwę pliku
        $targetFileName = $uploadDir . $hash . ".webp";
        //sprawdź czy plik przypadkiem już nie istnieje
        if(file_exists($targetFileName)) {
            die("BŁĄD: Podany plik już istnieje!");
        }
        //zaczytujemy cały obraz z folderu tymczasowego do stringa
        $imageString = file_get_contents($tempFileName);
        //generujemy obraz jako obiekt klasy GDImage
        //@ przed nazwa funkcji powoduje zignorowanie ostrzeżeń
        $gdImage = @imagecreatefromstring($imageString);
        //zapisz plik do docelowej lokalizacji
        imagewebp($gdImage, $targetFileName);
    }
}

?>