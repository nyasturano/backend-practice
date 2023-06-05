<?php

    global $db;

    $msg = array();
    
    $user = 'u52956';
    $pass = '6603699';
    $db = new PDO('mysql:host=localhost;dbname=u52956', $user, $pass, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

?>