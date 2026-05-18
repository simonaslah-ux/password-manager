<?php

session_start();
require_once '../classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = 'Užpildykite visus laukus.';
    } else {
        $userObj = new User();
        $user = $userObj->login($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['plain_password'] = $password;

            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Neteisingas vartotojo vardas arba slaptažodis.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h1>Prisijungimas</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Vartotojo vardas:</label><br>
        <input type="text" name="username"><br><br>

        <label>Slaptažodis:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Prisijungti</button>
    </form>

    <br>
    <a href="register.php">Registruotis</a><br>
    <a href="../index.php">Grįžti į pradžią</a>
</div>
</body>
</html>