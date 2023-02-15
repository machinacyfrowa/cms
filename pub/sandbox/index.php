<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFileInput">
            Wybierz plik do wgrania na serwer:
        </label><br>
        <input type="file" name="uploadedFile" id="uploadedFileInput" required><br>
        <input type="submit" value="Wyślij plik" name="submit"><br>
    </form>

    <?php
    //sprawdź czy został wysłany formularz
    if(isset($_POST['submit'])) 
    {
        //zdefiniuj folder do którego trafią pliki (ścieżka względem pliku index.php)
        $targetDir = "img/";

        //pobierz pierwotną nazwę pliku z tablicy $_FILES
        $sourceFileName = $_FILES['uploadedFile']['name'];

        //pobierz tymczasową ścieżkę do pliku na serwerze
        $tempURL = $_FILES['uploadedFile']['tmp_name'];

        //sprawdź czy mamy do czynienia z obrazem
        $imgInfo = getimagesize($tempURL);
        if(!is_array($imgInfo)) {
            die("BŁĄD: Przekazany plik nie jest obrazem!");
        }

        //wyciągnij pierwotne rozszerzenie pliku
        //$sourceFileExtension = pathinfo($sourceFileName, PATHINFO_EXTENSION);
        //zmień litery rozszerzenia na małe
        //$sourceFileExtension = strtolower($sourceFileExtension);
        /// niepotrzebne - generujemy webp

        //wygeneruj hash - nową nazwę pliku
        $hash = hash("sha256", $sourceFileName . hrtime(true) );
        $newFileName = $hash . ".webp";

        //zaczytujemy cały obraz z folderu tymczasowego do stringa
        $imageString = file_get_contents($tempURL);

        //generujemy obraz jako obiekt klasy GDImage
        //@ przed nazwa funkcji powoduje zignorowanie ostrzeżeń
        $gdImage = @imagecreatefromstring($imageString);

        //wygeneruj pełny docelowy URL
        $targetURL = $targetDir . $newFileName;

        //zbuduj docelowy URL pliku na serwerze
        //$targetURL = $targetDir . $sourceFileName;
        //wycofane na rzecz hasha

        //sprawdź czy plik przypadkiem już nie istnieje
        if(file_exists($targetURL)) {
            die("BŁĄD: Podany plik już istnieje!");
        }

        //przesuń plik do docelowej lokalizacji
        //move_uploaded_file($tempURL, $targetURL);
        //nieaktualne - generujemy webp
        imagewebp($gdImage, $targetURL);

        $db = new mysqli('localhost', 'root', '', 'cms');
        $query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?)");
        $dbTimestamp = date("Y-m-d H:i:s");
        $query->bind_param("ss", $dbTimestamp, $hash);
        if(!$query->execute())
            die("Błąd zapisu do bazy danych");

        echo "Plik został poprawnie wgrany na serwer";
    }
    ?>
</body>
</html>