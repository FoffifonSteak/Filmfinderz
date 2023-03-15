<?php

require './include/connection.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['message'] = "Le film a été supprimé du panier";
    $sql = 'DELETE FROM cart WHERE movie_id = :id AND user_id = :user_id';
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
}else {
    $_SESSION['message'] = "Le panier a été vidé";
    $sql = 'DELETE FROM cart WHERE user_id = :user_id';
    $query = $db->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
}


header('Location: cart.php');
?>
