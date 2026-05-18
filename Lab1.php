<?php

// Завдання 1
$numbers = [3, 5, 2, 7, 4];
$product = 1;
foreach ($numbers as $num) {
    $product *= $num;
}
echo "Завдання 1: Добуток = $product\n";


// Завдання 2
$numbers = [1, 6, 12, 28, 35, 100, 496, 200, 300, 50, 75, 312, 8128, 400, 999];
$perfect = [];
foreach ($numbers as $n) {
    if ($n < 2) continue;
    $sum = 1;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i === 0) {
            $sum += $i;
            if ($i !== $n / $i) $sum += $n / $i;
        }
    }
    if ($sum === $n) $perfect[] = $n;
}
echo "Завдання 2: Досконалі числа: " . implode(', ', $perfect) . "\n";


// Завдання 3
$numbers = [0, 3, 0, 7, 0, 1, 0];
$zeroCount = 0;
foreach ($numbers as $num) {
    if ($num === 0) $zeroCount++;
}
echo "Завдання 3: Кількість нулів = $zeroCount\n";


// Завдання 4
$numbers = [];
for ($i = 0; $i < 20; $i++) {
    $numbers[] = rand(1, 50);
}
$sumOfSquares = 0;
foreach ($numbers as $num) {
    if ($num % 2 !== 0) $sumOfSquares += $num * $num;
}
echo "Завдання 4: Масив: " . implode(', ', $numbers) . "\n";
echo "Завдання 4: Сума квадратів непарних = $sumOfSquares\n";


// Завдання 5
$numbers = [];
for ($i = 0; $i < 8; $i++) {
    $numbers[] = rand(1, 100);
}
echo "Завдання 5: До обміну: " . implode(', ', $numbers) . "\n";
$first = $numbers[0];
$numbers[0] = $numbers[count($numbers) - 1];
$numbers[count($numbers) - 1] = $first;
echo "Завдання 5: Після обміну: " . implode(', ', $numbers) . "\n";


// Завдання 6
$numbers = [];
for ($i = 0; $i < 10; $i++) {
    $numbers[] = rand(-50, 50);
}
$positives = array_filter($numbers, fn($n) => $n > 0);
$average = count($positives) > 0 ? array_sum($positives) / count($positives) : 0;
echo "Завдання 6: Масив: " . implode(', ', $numbers) . "\n";
echo "Завдання 6: Середнє додатних = $average\n";


// Завдання 7
function nameToEmail(string $fullName): string {
    $vowels = ['а','е','є','и','і','ї','о','у','ю','я'];
    $translitMap = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'h','ґ'=>'g','д'=>'d','е'=>'e','є'=>'ie',
        'ж'=>'zh','з'=>'z','и'=>'y','і'=>'i','ї'=>'i','й'=>'i','к'=>'k','л'=>'l',
        'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u',
        'ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'shch','ь'=>'',
    ];
    $parts = explode(' ', mb_strtolower($fullName));
    $translitParts = [];
    foreach ($parts as $part) {
        $result = '';
        $chars = preg_split('//u', $part, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < count($chars); $i++) {
            $c = $chars[$i];
            $prev = $i > 0 ? $chars[$i - 1] : null;
            if ($c === 'ю') {
                $result .= ($prev === null || in_array($prev, $vowels)) ? 'iu' : 'yu';
            } elseif ($c === 'я') {
                $result .= ($prev === null || in_array($prev, $vowels)) ? 'ia' : 'ya';
            } else {
                $result .= $translitMap[$c] ?? $c;
            }
        }
        $translitParts[] = $result;
    }
    return implode('.', $translitParts) . '@example.com';
}
$name = "Гарбузюк Олег";
echo "Завдання 7: Email = " . nameToEmail($name) . "\n";


// Завдання 8
$year = 2000;
$isMultipleOf400 = $year % 400 === 0 ? 'так' : 'ні';
echo "Завдання 8: $year кратний 400? — $isMultipleOf400\n";


// Завдання 9
$numbers = [];
for ($i = 0; $i < 10; $i++) {
    $numbers[] = rand(0, 100);
}
echo "Завдання 9: Масив: " . implode(', ', $numbers) . "\n";

$productEven = 1;
$hasEven = false;
$oddIndexElements = [];

foreach ($numbers as $index => $value) {
    if ($index % 2 === 0 && $value > 0) {
        $productEven *= $value;
        $hasEven = true;
    }
    if ($index % 2 !== 0 && $value > 0) {
        $oddIndexElements[] = $value;
    }
}

echo "Завдання 9: Добуток парних індексів (>0) = " . ($hasEven ? $productEven : 0) . "\n";
echo "Завдання 9: Елементи непарних індексів (>0): " . implode(', ', $oddIndexElements) . "\n";


// Завдання 10
$year = 2024;
if ($year < 1 || $year > 9999) {
    echo "Завдання 10: Рік поза допустимим діапазоном\n";
} elseif (($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0) {
    echo "Завдання 10: $year — високосний рік\n";
} else {
    echo "Завдання 10: $year — не високосний рік\n";
}
