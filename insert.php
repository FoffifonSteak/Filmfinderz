<?php
require './include/connection.php';

if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit();
}


$sql = "SELECT id, first_name, last_name FROM director";
$statement = $db->prepare($sql);
$statement->execute();

$directors = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require './head.php' ?>
    <link rel="stylesheet" href="./assets/css/insert.css">
    <title>Ajouter un film</title>
</head>
<body>
<?php require './header.php' ?>
<h1>Ajouter un nouveau film</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" required>

    <label for="release_date">Date de sortie :</label>
    <input type="date" id="release_date" name="release_date" required>

    <label for="langage">Langage :</label>
    <input type="text" id="langage" name="langage">

    <label for="rental_price">Prix de location :</label>
    <input type="number" id="rental_price" name="rental_price" required>

    <label for="resume">Résumé :</label>
    <textarea id="resume" name="resume" rows="5" required></textarea>

    <label for="director">Réalisateur :</label>
    <select id="director" name="director" required>
        <?php
        foreach ($directors as $director) {
            echo '<option value="' . $director['id'] . '">' . $director['first_name'] . ' ' . $director['last_name'] . '</option>';
        }
        ?>
    </select>


    <label for="rating">Note :</label>
    <select id="rating" name="rating" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>

    <label for="genres">Genres :</label>
    <select id="genres" name="genres[]" multiple required>
        <option value="1">Action</option>
        <option value="2">Comédie</option>
        <option value="3">Drame</option>
        <option value="4">Horreur</option>
        <option value="5">Science-fiction</option>
    </select>

    <label for="poster">Affiche :</label>
    <input type="file" id="poster" name="poster" accept="image/*" required>

    <input type="submit" value="Ajouter">
</form>
</body>
<?php require 'footer.html' ?>
</html>

<?php
$title = $_POST['title'] ?? null;
$date = $_POST['release_date'] ?? null;
$language = $_POST['langage'] ?? null;
$price = $_POST['rental_price'] ?? null;
$resume = $_POST['resume'] ?? null;
$director = $_POST['director'] ?? null;
$rating = $_POST['rating'] ?? null;
$genres = $_POST['genres'] ?? null;
$poster = $_POST['poster'] ?? null;

if (!empty($_POST['title']) && !empty($_POST['release_date']) && !empty($_POST['rental_price']) && !empty($_POST['resume']) && !empty($_POST['rating']) && isset($_POST['genres']) && !empty($_FILES['poster']['name'])) {
    $sql = "INSERT INTO movies (title, release_date, langage, rental_price, resume, rating, poster, id_director) VALUES (:title, :release_date, :langage, :rental_price, :resume, :rating, :poster, :id_director)";
    $destination = './assets/uploads/' . basename($_FILES['poster']['name']);
    move_uploaded_file($_FILES['poster']['tmp_name'], $destination);

    $query = $db->prepare($sql);
    $query->bindValue(':title', $title, PDO::PARAM_STR);
    $query->bindValue(':release_date', $date, PDO::PARAM_STR);
    $query->bindValue(':langage', $language, PDO::PARAM_STR);
    $query->bindValue(':rental_price', $price, PDO::PARAM_INT);
    $query->bindValue(':resume', $resume, PDO::PARAM_STR);
    $query->bindValue(':rating', $rating, PDO::PARAM_INT);
    $query->bindValue(':poster', $_FILES['poster']['name'], PDO::PARAM_STR);
    $query->bindValue(':id_director', $director, PDO::PARAM_INT);
    $query->execute();


    $lastId = $db->lastInsertId();
    foreach ($genres as $genre) {
        $sql = "INSERT INTO movie_genre (movie_id, genre_id) VALUES (:movie_id, :genre_id)";
        $query = $db->prepare($sql);
        $query->bindValue(':movie_id', $lastId, PDO::PARAM_INT);
        $query->bindValue(':genre_id', $genre, PDO::PARAM_INT);
        $query->execute();
    }

}

$_SESSION['message'] = "Le film a bien été ajouté";

?>
