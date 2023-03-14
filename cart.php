<?php
require './include/connection.php';

$user_id = $_SESSION['user_id'];
$total = 0;

$sql = 'SELECT * FROM cart INNER JOIN movies ON cart.movie_id = movies.id WHERE user_id = :user_id';
$query = $db->prepare($sql);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$cart = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/cart.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Mon Panier de Films</title>
</head>
<?php require './header.php' ?>
<body>
<div class="cartContainer">
    <div class="cart">
        <div class="cart-header">
            <div class="left">
                <div class="cart-header-item">Film</div>
            </div>
            <div class="right">
                <div class="cart-header-item">Prix</div>
                <div class="cart-header-item">Quantité</div>
                <div class="cart-header-item">Retirer</div>
            </div>

        </div>
        <?php foreach ($cart as $item) {
        $total += $item['rental_price'];
        ?>
        <div class="cart-item">
            <div class="leftInfo">
                <h3 class="cart-item-title"><?= $item['title'] ?></h3>
            </div>
            <div class="rightInfo">
                <span class="cart-item-price"><?= $item['rental_price'] ?></span>
                <input type="number" class="cart-item-quantity" value="1">
                <a href="removeFromCart.php?id=<?= "$item[id]" ?>" class="cart-item-remove">&times;</a>
            </div>
        </div>
        <?php  } ?>
        <div class="cart-total">
            Total : <?= number_format($total, 2) . "€" ?>
        </div>
        <div class="buttons">
            <a href="#" class="btn btn-primary">Acheter</a>
            <a href="removeFromCart.php" class="btn btn-secondary">Vider le Panier</a>
        </div>
        <?php if (empty($cart)) { ?>
            <div class="empty-cart">Votre panier est vide.</div>
        <?php } ?>

    </div>
</div>
<?php require './footer.html' ?>
</body>
</html>

