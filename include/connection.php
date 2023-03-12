<?php
session_start();
try {
    $db = new PDO(
        'mysql:host=localhost;dbname=filmfinderz;charset=utf8',
        'root',
        ''
    );
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

?>