<?php
require './include/connection.php';

$q = $_GET['q'] ?? '';

    $sql = "SELECT movies.id, movies.title, movies.release_date, movies.rating, movies.rental_price, movies.resume, movies.poster, director.first_name, director.last_name, GROUP_CONCAT(genres.name SEPARATOR ', ') AS genre_names
            FROM filmfinderz.movies
            INNER JOIN filmfinderz.movie_genre ON movies.id = movie_genre.movie_id
            INNER JOIN filmfinderz.genres ON movie_genre.genre_id = genres.id
            INNER JOIN filmfinderz.director ON movies.id_director = director.id
            WHERE (movies.title LIKE :q 
                   OR director.first_name LIKE :q
                   OR director.last_name LIKE :q)";

$genre = $_POST['genre'] ?? '';
$rating = $_POST['rating'] ?? '';


if (!empty($genre)) {
    $sql .= " AND genres.name = :genre";
}

if (!empty($rating)) {
    $sql .= " AND movies.rating = :rating";
}


$sql .= " GROUP BY movies.id";

$statement = $db->prepare($sql);
$statement->bindValue(':q', '%' . $q . '%');

if (!empty($genre)) {
    $statement->bindValue(':genre', $genre);
}

if (!empty($rating)) {
    $statement->bindValue(':rating', $rating);
}

$statement->execute();

$movies = $statement->fetchAll();

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/search.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
          integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Title</title>
</head>
<body>
<?php require './header.php' ?>
<main>
    <h1>Recherche de film</h1>
    <div id="search-box">
        <form action="search.php" method="get">
            <img id="loupe" src="./assets/images/loupe-arrondie.png" alt="loupe">
            <label>
                <input type="text" name="q" placeholder="Films, réalisateurs...">
            </label>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div id="filter-box">
        <h2>Filtrer les résultats :</h2>
        <form action="search.php" method="post">
            <div class="filter">
                <label for="genre-select">Genre :</label>
                <select id="genre-select" name="genre">
                    <option value="">Tous les genres</option>
                    <option value="action">Action</option>
                    <option value="drame">Drame</option>
                    <option value="comédie">Comédie</option>
                    <option value="horreur">Horreur</option>
                    <option value="science-fiction">Science-fiction</option>
                </select>

                <label for="rating-select">Note :</label>
                <select id="rating-select" name="rating">
                    <option value="">Toutes les notes</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <input type="submit" value="Filtrer">
            </div>
        </form>
    </div>
    <?php if (empty($movies)) { ?>
        <div style="margin: 0 auto; text-align: center">
            Aucun résultat
        </div>

    <?php } ?>
    <div id="results">
        <?php foreach ($movies as $movie) { ?>
            <div class="film-info" style="max-width: 550px;">
                <a href="movieInfo.php?id=<?= $movie['id'] ?>"><img src="./assets/uploads/<?= $movie['poster'] ?>"
                                                                    alt="Affiche de film"></a>
                <div class="film-details">
                    <h3><?php echo $movie['title'] ?></h3>
                    <p>Genre(s) : <?php echo $movie['genre_names'] ?></p>
                    <p>Réalisateur : <?php echo $movie['first_name'] . " " . $movie['last_name'] ?></p>
                    <p>Date de sortie : <?php echo $movie['release_date'] ?></p>
                    <p>Note : <?php echo $movie['rating'] ?>/5</p>
                    <p>Prix : <?= $movie['rental_price'] ?>€</p>
                    <div class="addCart">
                        <?php
                        if (isset($_SESSION['user_id'])) {
                            echo '<a href="addCart.php?id=' . $movie['id'] . '">Ajouter au panier</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>
<?php require './footer.html' ?>
</body>
</html>


