<?php
require './include/connection.php';

$comment = $_POST['comment'];
$movie_id = $_POST['movie_id'];
$user_id = $_SESSION['user_id'];

$sql = 'INSERT INTO comments (comment, movie_id, username_id) VALUES (:comment, :movie_id, :user_id)';
$query = $db->prepare($sql);
$query->bindValue(':comment', $comment, PDO::PARAM_STR);
$query->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

$query->execute();

header('Location: movieInfo.php?id=' . $movie_id);
?>