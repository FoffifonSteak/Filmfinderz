<?php
require './include/connection.php';

$movie_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if the movie is already in the cart
$sql = 'SELECT * FROM cart WHERE movie_id = :movie_id AND user_id = :user_id';
$query = $db->prepare($sql);
$query->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$movie = $query->fetch(PDO::FETCH_ASSOC);

// If the movie is already in the cart, update the quantity
if (is_null($movie)) {
    $sql = 'INSERT INTO cart (movie_id, user_id , quantity) VALUES (:movie_id, :user_id, 1)';
} else {
    $sql = 'UPDATE cart SET quantity = quantity + 1 WHERE movie_id = :movie_id AND user_id = :user_id';
}
$query = $db->prepare($sql);
$query->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();


header('Location: cart.php');

?>