<?php
require './include/connection.php';

$movie_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = 'INSERT INTO cart (movie_id, user_id , quantity) VALUES (:movie_id, :user_id, 1)';
$query = $db->prepare($sql);
$query->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();

header('Location: cart.php');

?>