<?php

require './include/connection.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['message'] = "La quantité du film a été modifié";
    $sql = 'UPDATE cart SET quantity = :quantity WHERE movie_id = :id AND user_id = :user_id';
    $query = $db->prepare($sql);
    $query->bindValue(':quantity', $_GET['quantity'], PDO::PARAM_INT);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
}


header('Location: cart.php');
?>
