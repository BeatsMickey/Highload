<?php

$start = microtime(true);

const REDIS_SERVER = '127.0.0.1';
const REDIS_PORT = 6379;

class redisCacheProvider {
    private $connection = null;
    private function getConnection(){
        if($this->connection===null){
            $this->connection = new Redis();
            $this->connection->connect(REDIS_SERVER, REDIS_PORT);
        }
        return $this->connection;
    }

    public function get($key){
        $result = false;
        if($c = $this->getConnection()){
            $result = $c->get($key);
        }
        return $result;
    }
    public function set($key, $value, $time=0){
        if($c=$this->getConnection()){
            $c->set($key, $value, $time);
        }
    }

    public function del($key){
        if($c=$this->getConnection()){
            $c->delete($key);
        }
    }

    public function clear(){
        if($c=$this->getConnection()){
            $c->flushDB();
        }
    }
}



$redis = new redisCacheProvider();

//$redis->del('html');
//exit;

if($html = $redis->get('html')) {
    var_dump($html);
    echo $html;
    var_dump(microtime(true) - $start);
    exit;
}


$db = new PDO('mysql:host=localhost;port=3306;dbname=highload;charset=utf8', 'user', 'Winitaly2006love()');
$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
$answer = $db->query('SELECT * FROM customers WHERE customerNumber = 103');
foreach ($answer as $row) {
    $answer = $row;
}

$html = "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Document</title>
</head>
<body>
    <p>customerNumber = {$answer['customerNumber']}</p>
    <p>phone = {$answer['phone']}</p>
    <p>city = {$answer['city']}</p>
</body>
</html>";

$redis->set('html', $html, 1000000000);

echo $html;

var_dump(microtime(true) - $start);
