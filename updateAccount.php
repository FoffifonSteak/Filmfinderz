<?php
require './include/connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: signIn.php');
    exit;
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
    <?php require './head.php' ?>
    <link rel="stylesheet" href="./assets/css/login.css">
    <title>Mon compte</title>
</head>
<body>
<?php require './header.php'; ?>
<main>
    <div class="card">
        <h4 class="title">Update !</h4>
        <form method="post" enctype="multipart/form-data">
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


