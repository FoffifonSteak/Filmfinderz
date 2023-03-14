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
                    <p>Film : <?= $director['title'] ?></p>
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
