<?php

// ====== Array ======
// 1

$colors = array('white', 'green', 'red');

sort($colors);

echo "<ul>";

foreach ($colors as $color) {
    echo "<li>" . $color . "</li>";
}

echo "</ul>";

// 2


$cities = [
    "Italy" => "Rome",
    "Luxembourg" => "Luxembourg",
    "Belgium" => "Brussels",
    "Denmark" => "Copenhagen",
    "Finland" => "Helsinki",
    "France" => "Paris",
    "Slovakia" => "Bratislava",
    "Slovenia" => "Ljubljana",
    "Germany" => "Berlin",
    "Greece" => "Athens",
    "Ireland" => "Dublin",
    "Netherlands" => "Amsterdam",
    "Portugal" => "Lisbon",
    "Spain" => "Madrid"
];

asort($cities);

foreach ($cities as $country => $capital) {
    echo "The capital of $country is $capital";
    echo "<br>";
}

// 3


$color = array(4 => 'white', 6 => 'green', 11 => 'red');

echo reset($color);

// 4


$numbers = [1, 2, 3, 4, 5];

$location = 4;
$newItem = "$";

array_splice($numbers, $location - 1, 0, [$newItem]);

foreach ($numbers as $number) {
    echo $number . " ";
}

// 5


$fruits = array(
    "d" => "lemon",
    "a" => "orange",
    "b" => "banana",
    "c" => "apple"
);

asort($fruits);

foreach ($fruits as $key => $value) {
    echo $key . " = " . $value;
    echo "<br>";
}

// 6


$temperatures = [
    78, 60, 62, 68, 71, 68, 73, 85, 66, 64,
    76, 63, 75, 76, 73, 68, 62, 73, 72, 65,
    74, 62, 62, 65, 64, 68, 73, 75, 79, 73
];

$average = array_sum($temperatures) / count($temperatures);

echo "Average Temperature is: " . round($average, 1);
echo "<br>";

sort($temperatures);

echo "List of five lowest temperatures: ";

for ($i = 0; $i < 5; $i++) {
    echo $temperatures[$i] . " ";
}

echo "<br>";

echo "List of five highest temperatures: ";

for ($i = count($temperatures) - 5; $i < count($temperatures); $i++) {
    echo $temperatures[$i] . " ";
}

// 7


$array1 = array("color" => "red", 2, 4);

$array2 = array(
    "a",
    "b",
    "color" => "green",
    "shape" => "trapezoid",
    4
);

$result = array_merge($array1, $array2);

echo "<pre>";
print_r($result);
echo "</pre>";

// 8


$colors = array("red", "blue", "white", "yellow");

function convertToUppercase($colors) {
    $result = [];

    foreach ($colors as $color) {
        $result[] = strtoupper($color);
    }

    return $result;
}

$upperColors = convertToUppercase($colors);

echo "<pre>";
print_r($upperColors);
echo "</pre>";


// ====== Function ======
// 1


function checkPrime($number) {
    if ($number <= 1) {
        return false;
    }

    for ($i = 2; $i < $number; $i++) {
        if ($number % $i == 0) {
            return false;
        }
    }

    return true;
}

$number = 3;

if (checkPrime($number)) {
    echo "$number is a prime number";
} else {
    echo "$number is not a prime number";
}

// 2

function reverseText($text) {
    return strrev($text);
}

$text = "remove";

echo reverseText($text);
// 3

function swapNumbers($x, $y) {
    $temp = $x;
    $x = $y;
    $y = $temp;

    echo "x = $x";
    echo "<br>";
    echo "y = $y";
}

$x = 12;
$y = 10;

swapNumbers($x, $y);
// 4

function checkArmstrong($number) {
    $original = $number;
    $sum = 0;

    while ($number > 0) {
        $digit = $number % 10;
        $sum += $digit ** 3;
        $number = (int) ($number / 10);
    }

    return $sum == $original;
}

$number = 407;

if (checkArmstrong($number)) {
    echo "$number is Armstrong Number";
} else {
    echo "$number is not Armstrong Number";
}
// 5

function checkPalindrome($text) {
    $text = strtolower($text);
    $text = preg_replace("/[^a-z0-9]/", "", $text);

    if ($text == strrev($text)) {
        return true;
    } else {
        return false;
    }
}

$text = "Eva, can I see bees in a cave?";

if (checkPalindrome($text)) {
    echo "Yes it is a palindrome";
} else {
    echo "No it is not a palindrome";
}
// 6


function removeDuplicates($numbers) {
    return array_values(array_unique($numbers));
}

$array1 = [2, 4, 7, 4, 8, 4];

$result = removeDuplicates($array1);

echo "<pre>";
print_r($result);
echo "</pre>";


// ====== Logical Statements and Operators ======
// 1

$firstInteger = 10;
$secondInteger = 10;

$sum = $firstInteger + $secondInteger;

if ($sum == 30) {
    echo $sum;
} else {
    echo "false";
}
// 2

$number = 20;

if ($number % 3 == 0) {
    echo "true";
} else {
    echo "false";
}
// 3

$number = 50;

if ($number >= 20 && $number <= 50) {
    echo "true";
} else {
    echo "false";
}
// 4

$num1 = 1;
$num2 = 5;
$num3 = 9;

if ($num1 >= $num2 && $num1 >= $num3) {
    echo $num1;
} elseif ($num2 >= $num1 && $num2 >= $num3) {
    echo $num2;
} else {
    echo $num3;
}
// 5

$units = 300;
$bill = 0;

if ($units <= 50) {
    $bill = $units * 2.50;

} elseif ($units <= 150) {
    $bill = (50 * 2.50) + (($units - 50) * 5.00);

} elseif ($units <= 250) {
    $bill = (50 * 2.50) + (100 * 5.00)
          + (($units - 150) * 6.20);

} else {
    $bill = (50 * 2.50) + (100 * 5.00)
          + (100 * 6.20) + (($units - 250) * 7.50);
}

echo "Electricity Bill: " . $bill . " JOD";
// 6

$num1 = 20;
$num2 = 5;
$operator = "+";

if ($operator == "+") {
    echo $num1 + $num2;

} elseif ($operator == "-") {
    echo $num1 - $num2;

} elseif ($operator == "*") {
    echo $num1 * $num2;

} elseif ($operator == "/") {
    if ($num2 != 0) {
        echo $num1 / $num2;
    } else {
        echo "Cannot divide by zero";
    }

} else {
    echo "Invalid Operator";
}
// 7

$age = 15;

if ($age >= 18) {
    echo "Eligible to vote";
} else {
    echo "Not eligible to vote";
}
// 8

$number = -60;

if ($number > 0) {
    echo "Positive";

} elseif ($number < 0) {
    echo "Negative";

} else {
    echo "Zero";
}
// 9

$grades = [60, 86, 95, 63, 55, 74, 79, 62, 50];

$sum = 0;

foreach ($grades as $grade) {
    $sum += $grade;
}

$average = $sum / count($grades);

if ($average < 60) {
    echo "F";

} elseif ($average < 70) {
    echo "D";

} elseif ($average < 80) {
    echo "C";

} elseif ($average < 90) {
    echo "B";

} else {
    echo "A";
}

// ====== Loops ======
// 1

for ($i = 1; $i <= 10; $i++) {
    echo $i;

    if ($i < 10) {
        echo "-";
    }
}
// 2

$sum = 0;

for ($i = 0; $i <= 30; $i++) {
    $sum += $i;
}

echo "Total: " . $sum;

// 3

for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($j <= 5 - $i) {
            echo "A ";
        } else {
            echo chr(64 + $i) . " ";
        }
    }

    echo "<br>";
}

// 4

for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($j <= 5 - $i) {
            echo "1 ";
        } else {
            echo $i . " ";
        }
    }

    echo "<br>";
}

// 5

for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($i == $j) {
            echo $i . " ";
        } else {
            echo "0 ";
        }
    }

    echo "<br>";
}

// 6

$number = 5;
$factorial = 1;

for ($i = 1; $i <= $number; $i++) {
    $factorial *= $i;
}

echo $factorial;

// 7

echo "<table border='1' cellpadding='3px' cellspacing='0px'>";

for ($i = 1; $i <= 6; $i++) {
    echo "<tr>";

    for ($j = 1; $j <= 5; $j++) {
        $result = $i * $j;

        echo "<td>$i * $j = $result</td>";
    }

    echo "</tr>";
}

echo "</table>";

// ====== PHP String and String Functions ======
// 1

$text = "orange coding academy";

echo strtoupper($text);
echo "<br>";

echo strtolower($text);
echo "<br>";

echo ucfirst($text);
echo "<br>";

echo ucwords($text);

// 2

$time = "085119";

$formattedTime = substr($time, 0, 2) . ":"
               . substr($time, 2, 2) . ":"
               . substr($time, 4, 2);

echo $formattedTime;

// 3

$sentence = "I am a full stack developer at orange coding academy";

$word = "Orange";

if (stripos($sentence, $word) !== false) {
    echo "Word Found";
} else {
    echo "Word Not Found";
}

// 4

$url = "www.orange.com/index.php";

$fileName = basename($url);

echo $fileName;

// 5

$email = "info@orange.com";

$username = strstr($email, "@", true);

echo $username;

// 6

$email = "info@orange.com";

$result = substr($email, -3);

echo $result;

// 7

$characters = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz";

$password = substr(str_shuffle($characters), 0, 8);

echo $password;

// 8

$sentence = "That new trainee is so genius.";

$newSentence = preg_replace("/^\S+/", "Our", $sentence);

echo $newSentence;