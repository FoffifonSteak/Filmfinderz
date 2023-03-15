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
    <title>Mon panier</title>
    <link rel="stylesheet" href="./assets/css/cart.css?v=<?= time() ?>">
    <?php require './head.php' ?>
</head>
<body>
<?php require './header.php' ?>
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
            $total += $item['rental_price'] * $item['quantity'];
            ?>
            <div class="cart-item">
                <div class="leftInfo">
                    <h3 class="cart-item-title"><?= $item['title'] ?></h3>
                </div>
                <div class="rightInfo">
                    <span class="cart-item-price"><?= $item['rental_price'] ?></span>
                    <input type="number" id="input-<?= "$item[id]" ?>" class="quantity cart-item-quantity"
                           value="<?= $item['quantity'] ?>">
                    <a href="updateQuantity.php?id=<?= "$item[id]" ?>&quantity=<?= $item['quantity'] ?>" id="movie-<?= "$item[id]" ?>" >Mettre à jour</a>
                    <a href="removeFromCart.php?id=<?= "$item[id]" ?>" class="cart-item-remove">&times;</a>
                </div>
            </div>
        <?php } ?>
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
<script>
    const notyf = new Notyf()
    <?php if (isset($_SESSION['message'])) { ?>
    notyf.success('<?= $_SESSION['message'] ?>')
    <?php unset($_SESSION['message']);
    } ?>


    document.querySelectorAll(".quantity").forEach((input) => {
        input.addEventListener("change", (e) => {
            const id = e.target.id.split("-")[1]
            const quantity = e.target.value;
            document.getElementById(`movie-${id}`).href = `updateQuantity.php?id=${id}&quantity=${quantity}`
        })
    })
</script>
</html>

