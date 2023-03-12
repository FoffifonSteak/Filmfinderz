<?php
require './include/connection.php';

$sql = 'SELECT title, release_date, rating, rental_price, resume, poster, director.first_name, director.last_name, GROUP_CONCAT(genres.name SEPARATOR ", ") AS genre_names
        FROM filmfinderz.movies
        INNER JOIN filmfinderz.movie_genre ON movies.id = movie_genre.movie_id
        INNER JOIN filmfinderz.genres ON movie_genre.genre_id = genres.id
        INNER JOIN filmfinderz.director ON movies.id_director = director.id
        WHERE movies.id = :id';

$statement = $db->prepare($sql);
$statement->execute([
    'id' => $_GET['id']
]);

$movie = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/movieInfo.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
    <h1><?php echo $movie['title']; ?></h1>
    <div class="poster">
        <img src="./assets/uploads/<?= $movie['poster'] ?>" alt="Image 1">
    </div>
    <div class="trailer">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/tgbNymZ7vqY" frameborder="0"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
    <div class="summary">
        <h2>Résumé</h2>
        <p><?php echo $movie['resume']; ?></p>
    </div>
    <div class="info">
        <h2>Informations</h2>
        <ul>
            <li><span>Date de sortie :</span> <?php echo $movie['release_date']; ?></li>
            <li><span>Genre :</span> <?php echo $movie['genre_names']; ?></li>
            <li><span><a id="director" href="director.php">Réalisateur :</a></span> <?php echo $movie['first_name'] . ' ' . $movie['last_name']; ?></li>
            <li><span>Note :</span> <?php echo $movie['rating']; ?>/5</li>
            <li><span>Prix de location :</span> <?php echo $movie['rental_price']; ?> €</li>
        </ul>
    </div>
    <div class="addCart">
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a href="addCart.php?id=' . $_GET['id'] . '">Ajouter au panier</a>';
        }
        ?>
    </div>
    <div class="comments">
        <h2>Commentaires :</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="addComment.php" method="post">
                <label for="comment">Comment :</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
                <input type="hidden" name="movie_id" value="<?= $_GET['id'] ?>">
                <br>
                <button type="submit">Post</button>
            </form>
        <?php endif; ?>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <ul>
                    <li>
                        <h3><?= $comment['username'] ?></h3>
                        <p><?= $comment['comment'] ?></p>
                    </li>
                </ul>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun commentaire</p>
        <?php endif; ?>


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