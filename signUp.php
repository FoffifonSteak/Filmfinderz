<?php
require './include/connection.php';

$csrf_token = $_SESSION['csrf_token'] = bin2hex(random_bytes(32));


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require './head.php' ?>
    <link rel="stylesheet" href="./assets/css/login.css">
    <title>FilmFinderz - Sign Up</title>
</head>
<body>
<header>
    <div class="logo">
        <a href="index.php"><img src="./assets/images/logo.png" alt="Mon logo"></a>
    </div>
    <div class="rightHead">
        <div class="search">
            <a href="search.php"><img src="./assets/images/loupe-arrondie.png" alt="loupe"></a>
        </div>
        <div class="connect">
            <a href="signIn.php">Se connecter</a>
        </div>
        <div class="language">
            <img src="./assets/images/france.png" alt="flag">
        </div>
    </div>
</header>
<main>
    <div class="card">
        <h4 class="title">Sign Up!</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="field">
                <i class="fa-solid fa-user"></i>
                <input autocomplete="off" placeholder="Username" class="input-field" name="username"
                       type="text">
            </div>
            <div class="field">
                <i class="fa-solid fa-at"></i>
                <input autocomplete="off"placeholder="Email" class="input-field" name="email"
                       type="email">
            </div>
            <div class="field">
                <i class="fa-solid fa-lock"></i>
                <input autocomplete="off" placeholder="Password" class="input-field" name="password"
                       type="password">
            </div>
            <button class="btn" type="submit">Register</button>
        </form>
    </div>
</main>
</body>
<?php
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$date = date('Y-m-d H:i:s');
$password_hash = password_hash($password, PASSWORD_DEFAULT);

if (!isset($username) || !isset($email) || !isset($password)) {
    echo "Veuillez remplir les champs";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email invalide";
    exit;
}

if (strlen($password) < 8) {
    echo "Mot de passe trop court";
    exit;
}

if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    echo "Invalid CSRF token";
    exit;
}

$sql = "INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, :created_at)";
$stmt = $db->prepare($sql);

$stmt->bindValue(':username', $username);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $password_hash);
$stmt->bindValue(':created_at', $date);

$stmt->execute();

$_SESSION['user_id'] = $db->lastInsertId();
header('Location: index.php');


?>
</html>