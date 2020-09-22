<?php

require_once('vendor/autoload.php');


use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('app');
$log->pushHandler(new StreamHandler('log/my.log', Logger::DEBUG));

error_reporting(E_ALL);
ini_set('display_errors', 1);


$log->debug("К-во памяти на начало программы " . memory_get_usage());




// мой поиск простого n-ого числа на основе решета Аткина
function getSimpleN($number, $log) {

    $log->notice("Вычисляем простое число под номером {$number}");



    $log->info("stage 1");
    $memStage1 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 1 " . $memStage1);

    $limit = $number * 7.05;
    $sqr_limit = (int)sqrt($limit);
    $is_prime = [];

    $log->info("stage 2");
    $memStage2 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 2 " . $memStage2);
    $log->debug("Приращение памяти " . ($memStage2 - $memStage1));

    for ($i = 0; $i <= $limit; $i++) {
        $is_prime[$i] = false;
    }

    $log->info("stage 3");
    $memStage3 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 3 " . $memStage3);
    $log->debug("Приращение памяти " . ($memStage3 - $memStage2));

    for ($i = 1; $i <= $sqr_limit; $i++) {
        $x2 = $i * $i;
        for ($j = 1; $j <= $sqr_limit; $j++) {
            $y2 = $j * $j;
            $n = 4 * $x2 + $y2;
            if ($n <= $limit && ($n % 12 == 1 || $n % 12 == 5)) {
                $is_prime[$n] = true;
            }

            $n -= $x2;
            if ($n <= $limit && $n % 12 == 7) {
                $is_prime[$n] = true;
            }

            $n -= 2 * $y2;
            if ($i > $j && $n <= $limit && $n % 12 == 11) {
                $is_prime[$n] = true;
            }
        }
    }

    $log->info("stage 4");
    $memStage4 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 4 " . $memStage4);
    $log->debug("Приращение памяти " . ($memStage4 - $memStage3));

    for ($i = 5; $i <= $sqr_limit; $i++) {
        if ($is_prime[$i]) {
            $n = $i * $i;
            for ($j = $n; $j <= $limit; $j += $n) {
                $is_prime[$j] = false;
            }
        }
    }

    $log->info("stage 5");
    $memStage5 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 5 " . $memStage5);
    $log->debug("Приращение памяти " . ($memStage5 - $memStage4));

    $is_prime[2] = true;
    $is_prime[3] = true;
    $is_prime[5] = true;

    $log->info("stage 6");
    $memStage6 = memory_get_usage();
    $log->debug("К-во памяти на момент stage 6 " . $memStage6);
    $log->debug("Приращение памяти " . ($memStage6 - $memStage5));

    $count = 3;
    for ($i = 6; $i <= $limit; $i++) {
        if ($is_prime[$i] && $i % 3 != 0 && $i % 5 !=  0) {
            $count++;
            if ($count == $number) {
                $log->debug("К-во памяти на момент выхода из функции " . memory_get_usage());
                return $i;
            }
        }
    }
}


$n = 10001;
echo "Простое число под номером {$n} равно " . getSimpleN($n, $log) . "<br>";
$log->debug("К-во памяти в конце скрипта " . memory_get_usage());


