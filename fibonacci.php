<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fibonacci Generator</title>
</head>
<body>
    <h1>Fibonacci-reeks</h1>

    <form method="post" action="">
        Voer een getal in: <input type="number" name="n" required>
        <input type="submit" value="Genereer Fibonacci-reeks">
    </form>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $n = $_POST['n'];


        if (is_numeric($n) && $n > 0) {
            generateFibonacci($n);
        } else {
            echo "Voer een geldig getal groter dan 0 in.";
        }
    }

    function generateFibonacci($n) {
        $fib = [1, 2]; 

        for ($i = 2; $i < $n; $i++) {
            $fib[] = $fib[$i-1] + $fib[$i-2];
        }

        echo "<h2>Fibonacci reeks$n</h2>";
        echo implode(", ", $fib);
    }
    ?>
</body>
</html>
