<?php
require './include/connection.php';

$sql = 'SELECT title, first_name, last_name, birthday FROM director
LEFT JOIN movies ON director.id = movies.id_director;';

$statement = $db->prepare($sql);
$statement->execute();
$directors = $statement->fetchAll(PDO::FETCH_ASSOC);



?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/director.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Document</title>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.php"><img src="./assets/images/logo.png" alt="Mon logo"></a>
    </div>
    <div class="rightHead">
        <div class="insert">
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) : ?>
                <a href="insert.php">Ajouter un film</a>
            <?php endif; ?>
        </div>
        <div class="search">
            <a href="search.php"><img src="./assets/images/loupe-arrondie.png" alt="loupe"></a>
        </div>
        <div class="connect">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a href="logout.php">Se déconnecter</a>';
                echo '<a style="margin-left: 0; margin-right: 40px" href="cart.php"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>';
            } else {
                echo '<a href="signIn.php">Se connecter</a>';
            }
            ?>
        </div>
        <div class="language">
            <img src="./assets/images/france.png" alt="flag">
        </div>
    </div>
</header>
<main>
    <h1>Réalisateurs</h1>
    <div id="results">
        <?php foreach ($directors as $director) {
            $birthdate = date_create($director['birthday']);
            ?>
            <div class="result">
                <h2><?= $director['first_name'] ?> <?= $director['last_name'] ?></h2>
                <p>Année de naissance : <?= date_format($birthdate, 'd/m/Y') ?></p>
                <?php if ($director['title'] !== null) { ?>
                    <p>Film : <?= $director['title'] ?></p>
                <?php } else { ?>
                    <p>Ce réalisateur n'a pas de films dans la base de données</p>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</main>
<footer>
    <div class="cartContainer">
        <div class="footer-row">
            <div class="footer-column">
                <h4>Nos produits</h4>
                <ul>
                    <li><a href="#">Films</a></li>
                    <li><a href="#">Réalisateurs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Nos services</h4>
                <ul>
                    <li><a href="#">Recherche d'un film</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Evaluations</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Nous suivre</h4>
                <ul class="social-icons">
                    <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter"></i></i></a></li>
                </ul>
                <a href="#top" class="back-to-top-button">Retour en haut</a>
            </div>
        </div>
    </div>
</footer>
</body>

</html>
