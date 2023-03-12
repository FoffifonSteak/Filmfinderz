<?php
require './include/connection.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="./assets/css/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./assets/css/login.css?v=<?= time() ?>">
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
        <div class="search">
            <a href="#"><img src="./assets/images/loupe-arrondie.png" alt="loupe" style="margin-right: 50px"></a>
        </div>
        <div class="language">
            <img src="./assets/images/france.png" alt="flag">
        </div>
    </div>
</header>
<main>
    <div class="card">
        <h4 class="title">Sign In!</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="field">
                <i class="fa-solid fa-user"></i>
                <input autocomplete="off" placeholder="Username" class="input-field" name="username"
                       type="text">
            </div>
            <div class="field">
                <i class="fa-solid fa-lock"></i>
                <input autocomplete="off" placeholder="Password" class="input-field" name="password"
                       type="password">
            </div>
            <button class="btn" type="submit">Log in</button>
            <br>
            <span>Vous n'avez pas de compte ? <a href="signUp.php" class="signUp">Cliquez-ici</a></span>
        </form>
    </div>
</main>
</body>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Token CSRF invalide";
        exit;
    }

    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!isset($username) || !isset($password)) {
        echo "Veuillez remplir tous les champs";
        exit;
    }

    $sql = "SELECT id, password, is_admin FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo "Nom d'utilisateur ou mot de passe incorrect";
        exit;
    }


    $_SESSION['user_id'] = $user['id'];
    if ($user['is_admin']) {
        $_SESSION['is_admin'] = true;

    } else {
        $_SESSION['is_admin'] = false;
    }

    header('Location: index.php');
    setcookie('lastLogin', date('Y-m-d H:i:s'), time() + 365 * 24 * 3600, null, null, false, true);
    exit;
}

?>
</html>