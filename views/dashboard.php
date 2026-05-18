<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Valdymo skydelis</title>
</head>
<body>

    <h1>Slaptažodžių saugykla</h1>

    <p>Sveiki, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <ul>
         <li><a href="add_password.php">Generuoti ir išsaugoti slaptažodį</a></li>
         <li><a href="passwords.php">Peržiūrėti išsaugotus slaptažodžius</a></li>
        <li><a href="../logout.php">Atsijungti</a></li>
    </ul>

</body>
</html>