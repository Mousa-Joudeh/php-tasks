<?php

// ====== PHP String and String Functions ======
// 1

$text = "Twinkle, twinkle, little star.";

$words = explode(" ", $text);

var_dump($words);

// 2

$letter = "a";

if ($letter == "z") {
    echo "a";
} else {
    echo ++$letter;
}

// 3

$text = "The brown fox";

$newText = substr_replace($text, "quick ", 4, 0);

echo $newText;


$text = "The quick brown fox";

$words = explode(" ", $text);

echo $words[0];

// 4

$number = "0000657022.24";

$result = str_replace("0", "", $number);

echo $result;

// 5

$text = "The quick brown fox jumps over the lazy dog---";

$result = rtrim($text, "-");

echo $result;

// 6

$text = "The quick brown fox jumps over the lazy dog";

$words = explode(" ", $text);

$firstFive = array_slice($words, 0, 5);

echo implode(" ", $firstFive);
// 7

$number = "2,543.12";

$result = str_replace(",", "", $number);

echo $result;

// ====== Loops ======
// 1

$first = 0;
$second = 1;
$n = 10;

for ($i = 0; $i < $n; $i++) {
    echo $first;

    if ($i < $n - 1) {
        echo ", ";
    }

    $next = $first + $second;
    $first = $second;
    $second = $next;
}

// 2

$n = 5;
$number = 1;

for ($i = 1; $i <= $n; $i++) {

    for ($j = 1; $j <= $i; $j++) {
        echo $number . " ";
        $number++;
    }

    echo "<br>";
}

// 3

echo "<pre>";

for ($i = 1; $i <= 5; $i++) {

    for ($s = 1; $s <= 5 - $i; $s++) {
        echo "  ";
    }

    for ($j = 0; $j < $i; $j++) {
        echo chr(65 + $j) . " ";
    }

    echo "\n";
}

for ($i = 4; $i >= 1; $i--) {

    for ($s = 1; $s <= 5 - $i; $s++) {
        echo "  ";
    }

    for ($j = 0; $j < $i; $j++) {
        echo chr(65 + $j) . " ";
    }

    echo "\n";
}

echo "</pre>";

// ====== Logical Statements and Operators  ======
// 1

$year = 2013;

if (($year % 400 == 0) || ($year % 4 == 0 && $year % 100 != 0)) {
    echo "This year is a leap year";
} else {
    echo "This year is not a leap year";
}

// 2

$temperature = 27;

if ($temperature < 20) {
    echo "It is wintertime!";
} else {
    echo "It is summertime!";
}

// 3

$firstInteger = 2;
$secondInteger = 2;

$sum = $firstInteger + $secondInteger;

if ($firstInteger == $secondInteger) {
    $sum = $sum * 3;
}

echo $sum;

// 4

for ($i = 200; $i <= 250; $i++) {
    if ($i % 4 == 0) {
        echo $i;

        if ($i < 248) {
            echo ",";
        }
    }
}

// 5

$numbers = [];

for ($i = 11; $i <= 20; $i++) {
    $numbers[] = $i;
}

shuffle($numbers);

for ($i = 0; $i < 10; $i++) {
    echo $numbers[$i] . " ";
}