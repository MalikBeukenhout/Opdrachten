<?php
// Simple Calculator
echo "Simple Calculator\n";

while (true) {
    // Get user inputs
    $num1 = getNumber("Enter first number: ");
    $operation = getOperation("Enter operation (+, -, *, /): ");
    $num2 = getNumber("Enter second number: ");

    // Perform operation
    $result = calculate($num1, $operation, $num2);

    // Display result
    echo "Result: $result\n\n";

}

function getNumber($message){
    return (float)readline($message);
}

function getOperation($message){
    return readline($message);
}

function calculate($num1, $operation, $num2) {
    if ($operation == "+") {
        return $num1 + $num2;
    } elseif ($operation == "-") {
        return $num1 - $num2;
    } elseif ($operation == "*") {
        return $num1 * $num2;
    } elseif ($operation == "/") {
        if ($num2 != 0) {
            return $num1 / $num2;
        } else {
            return "Error: Division by zero!";
        }
    } else {
        return "Invalid operation!";
    }
}
?>
