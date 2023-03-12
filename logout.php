<?php
session_start();
//delete cookie
setcookie('lastLogin', '', time() - 3600);
//delete session
unset($_SESSION['user_id']);
unset($_SESSION['is_admin']);
header('Location: index.php');
session_destroy();
exit;
?>
