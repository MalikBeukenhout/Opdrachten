<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tafelkaart Generator</title>
</head>
<body>
    <h1>Genereer de tafelkaart van een getal</h1>
    
    <form method="post" action="">
        Voer een getal in: <input type="number" name="n" required>
        <input type="submit" value="Genereer">
    </form>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $n = $_POST['n'];


        if (is_numeric($n) && $n > 0) {
            generateTableForNumber($n);
        } else {
            echo "Voer een geldig getal groter dan 0 in.";
        }
    }


    function generateTableForNumber($n) {
        echo "<h2>Tafel van $n</h2>";

        for ($i = 1; $i <= 10; $i++) {
            echo "$n x $i = " . ($n * $i) . "<br>";
        }
    }
    ?>
</body>
</html>
