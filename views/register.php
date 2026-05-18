<?php

require_once '../classes/User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = 'Užpildykite visus laukus.';
    } else {
        $user = new User();

        if ($user->usernameExists($username)) {
            $message = 'Toks vartotojas jau egzistuoja.';
        } else {
            if ($user->register($username, $password)) {
                $message = 'Registracija sėkminga. Galite prisijungti.';
            } else {
                $message = 'Įvyko klaida registracijos metu.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
</head>
<body>

    <h1>Registracija</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Vartotojo vardas:</label><br>
        <input type="text" name="username"><br><br>

        <label>Slaptažodis:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Registruotis</button>
    </form>

    <br>
    <a href="../index.php">Grįžti į pradžią</a>

</body>
</html>