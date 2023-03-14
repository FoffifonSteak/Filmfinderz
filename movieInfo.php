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
<html lang="fr">
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
<?php require './header.php' ?>
<main>
    <h1><?php echo $movie['title']; ?></h1>
    <div class="poster">
        <img src="./assets/uploads/<?= $movie['poster'] ?>" alt="Image 1">
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
            <li><span><a id="director"
                         href="director.php">Réalisateur :</a></span> <?php echo $movie['first_name'] . ' ' . $movie['last_name']; ?>
            </li>
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
                <input type="submit" value="Envoyer">
            </form>
        <?php endif; ?>
        <?php
        $sql = 'SELECT comments.comment, comments.username_id, users.username
                FROM comments
                INNER JOIN users ON comments.username_id = users.id
                WHERE comments.movie_id = :id';
        $statement = $db->prepare($sql);

        $statement->execute([
            'id' => $_GET['id']
        ]);

        $comments = $statement->fetchAll();
        ?>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-list">
                    <ul>
                        <li>
                            <h3><?= $comment['username'] ?></h3>
                            <p><?= $comment['comment'] ?></p>
                        </li>
                    </ul>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>
<?php require './footer.html' ?>
</body>
</html>