<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        img {
            display: block;
            margin: 10px auto;
        }
        </style>
<?php
$db = new mysqli('localhost', 'root', '', 'cms');
$q = "SELECT * FROM post ORDER BY timestamp DESC";
$result = $db->query($q);
while($row = $result->fetch_assoc()) {
    $hash = $row['filename'];
    $url = "img/" . $hash . ".webp";
    echo "<img src=\"$url\">";
}
?>
</body>
</html>