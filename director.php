<?php
require './include/connection.php';

$sql = "SELECT director.id, birthday, first_name, last_name, GROUP_CONCAT(IFNULL(movies.title, 'N/A') SEPARATOR '; ') AS title
        FROM director
        LEFT JOIN movies ON director.id = movies.id_director
        GROUP BY director.id";

$statement = $db->prepare($sql);
$statement->execute();

$directors = $statement->fetchAll();

?>

<!doctype html>
<html lang="fr">
<head>
    <?php require './head.php' ?>
    <link rel="stylesheet" href="./assets/css/director.css?v=<?= time() ?>">
</head>
<body>
<?php require './header.php' ?>
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
                    <p>Films : <?= $director['title'] ?></p>
                <?php } else { ?>
                    <p>Ce réalisateur n'a pas de films dans la base de données</p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</main>

<?php require './footer.html' ?>
</body>

</html>
