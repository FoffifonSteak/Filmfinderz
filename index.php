<?php
require './include/connection.php';

$sql = "SELECT * FROM movies";
$statement = $db->prepare($sql);
$statement->execute();

$movies = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/homePage.css?v=<?= time() ?>">
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
    <div class="atFirst">
        <section class="featured-film">
            <div class="overlay">
                <h1>Film à la une</h1>
                <p>Résumé du film. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis leo semper,
                    sagittis ipsum eu, molestie nisl.</p>
                <a href="#">Regarder la bande-annonce</a>
            </div>
        </section>
        <div class="seeMore">
            <a href="movieInfo.php?id=<?= $movies[0]['id'] ?>">Voir plus</a>
        </div>
    </div>
    <h2 class="moment">En ce moment :</h2>
    <div class="carousel">
        <div class="carousel-container">

            <?php
            $i = 0;

            foreach ($movies as $movie):
                if ($i == 4) break;
                ?>
                <div class="carousel-block">
                    <a href="movieInfo.php?id=<?= $movie['id'] ?>">
                        <img src="./assets/uploads/<?= $movie['poster'] ?>" alt="Image 1">
                    </a>
                    <h3><?= $movie['title'] ?></h3>
                </div>
                <?php
                $i++;
            endforeach;
            ?>


        </div>
        <button class="carousel-prev">&#10094;</button>
        <button class="carousel-next">&#10095;</button>
    </div>
    <div class="director">
        <div class="pictureDirector">
            <img src="./assets/images/director.png" alt="director picture">
        </div>
        <div class="infoDirector">
            Martin Dupont est un réalisateur de films de renom, célèbre pour ses œuvres dramatiques qui abordent souvent des questions sociales importantes. Il a commencé sa carrière en tant que cinéaste indépendant, en réalisant des courts métrages primés dans des festivals du monde entier.
            <br><br>
            Grâce à sa créativité, son talent et son engagement à raconter des histoires significatives, Martin a rapidement obtenu une reconnaissance internationale et a commencé à travailler sur des longs métrages. Ses films acclamés par la critique, tels que "La vie en fragments" et "Les cicatrices du passé", ont été appréciés pour leur style visuel poétique et émouvant, ainsi que pour leur capacité à traiter de sujets complexes d'une manière simple et accessible.

        </div>
    </div>
    <div class="buttonSignUp">
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo '<a href="signIn.php">Identifiez-vous !</a>';
        }
        ?>
    </div>
</main>
<footer>
    <div class="cartContainer">
        <div class="footer-row">
            <div class="footer-column">
                <h4>Nos produits</h4>
                <ul>
                    <li><a href="search.php">Films</a></li>
                    <li><a href="director.php">Réalisateurs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Nos services</h4>
                <ul>
                    <li><a href="search.php">Recherche d'un film</a></li>
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
<script>
    const carouselContainer = document.querySelector('.carousel-container');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    let currentIndex = 0;
    const maxIndex = carouselContainer.children.length - 1;

    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = maxIndex;
        }
        carouselContainer.style.transform = `translateX(-${currentIndex * 25}%)`;
    });

    nextButton.addEventListener('click', () => {
        if (currentIndex < maxIndex) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        carouselContainer.style.transform = `translateX(-${currentIndex * 25}%)`;
    });

</script>

</html>


