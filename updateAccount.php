<?php
require './include/connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: signIn.php');
    exit;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$sql = 'SELECT username, email FROM users WHERE id = :id';

$query = $db->prepare($sql);

$query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

$query->execute();

$user = $query->fetch();

?>
<!DOCTYPE html>
<html lang="fr">
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
<?php require './header.php'; ?>
<main>
    <div class="card">
        <h4 class="title">Update !</h4>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="field">
                <i class="fa-solid fa-user"></i>
                <input autocomplete="off" placeholder="Username" class="input-field" name="username"
                       type="text" value="<?php echo $user['username']; ?>">
            </div>
            <div class="field">
                <i class="fa-solid fa-at"></i>
                <input autocomplete="off"placeholder="Email" class="input-field" name="email"
                       type="email" value="<?php echo $user['email']; ?>">
            </div>
            <div class="field">
                <i class="fa-solid fa-lock"></i>
                <input autocomplete="off" placeholder="Password" class="input-field" name="password"
                       type="password">
            </div>
            <button class="btn" type="submit">Update</button>
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
    $email = $_POST['email'] ?? null;

    if (!isset($username) || !isset($email)) {
        echo "Veuillez remplir tous les champs";
        exit;
    }

    $sql = "UPDATE users SET username = :username, email = :email";


    if (!empty($_POST['password'])) {
        $sql .= ", password = :password";
    }

    $sql .= " WHERE id = :id";

    $query = $db->prepare($sql);

    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->bindValue(':email', $email, PDO::PARAM_STR);


    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query->bindValue(':password', $password, PDO::PARAM_STR);
    }

    $query->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

    $query->execute();

    header('Location: index.php');
}

?>
</html>


