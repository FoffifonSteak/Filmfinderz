<?php
require './include/connection.php';

$sql = "SELECT * FROM movies";
$statement = $db->prepare($sql);
$statement->execute();

$movies = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<title>Accueil</title>
<?php require './head.php' ?>
<body>
<?php require './header.php' ?>
<main>
    <div class="atFirst">
        <section class="featured-film">
            <div class="overlay">
                <h1>Film à la une, <?= $movies[0]['title'] ?></h1>
                <p><?= $movies[0]['resume'] ?></p>
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
                        <img src="./assets/uploads/<?= $movie['poster'] ?>" alt="poster du film">
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
<?php require './footer.html' ?>

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


