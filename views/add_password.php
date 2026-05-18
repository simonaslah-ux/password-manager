<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/PasswordGenerator.php';
require_once '../classes/PasswordEntry.php';

$generatedPassword = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['generate'])) {
        $length = (int) $_POST['length'];
        $lowercase = (int) $_POST['lowercase'];
        $uppercase = (int) $_POST['uppercase'];
        $numbers = (int) $_POST['numbers'];
        $specials = (int) $_POST['specials'];

        if ($lowercase + $uppercase + $numbers + $specials <= $length) {
            $generator = new PasswordGenerator($length, $lowercase, $uppercase, $numbers, $specials);
            $generatedPassword = $generator->generate();
        } else {
            $message = 'Klaida: simbolių kiekis negali būti didesnis už bendrą ilgį.';
        }
    }

    if (isset($_POST['save'])) {
        $title = trim($_POST['title']);
        $password = trim($_POST['password']);

        if (empty($title) || empty($password)) {
            $message = 'Įveskite pavadinimą ir slaptažodį.';
        } else {
            $passwordEntry = new PasswordEntry();

            $saved = $passwordEntry->savePassword(
                $_SESSION['user_id'],
                $title,
                $password,
                $_SESSION['plain_password']
            );

            if ($saved) {
                $message = 'Slaptažodis sėkmingai išsaugotas.';
            } else {
                $message = 'Nepavyko išsaugoti slaptažodžio.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Slaptažodžio generavimas</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <h1>Slaptažodžio generavimas ir saugojimas</h1>

    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Generuoti slaptažodį</h2>

    <form method="POST">
        <label>Slaptažodžio ilgis:</label><br>
        <input type="number" name="length" value="12" min="4" required><br><br>

        <label>Mažųjų raidžių kiekis:</label><br>
        <input type="number" name="lowercase" value="3" min="0" required><br><br>

        <label>Didžiųjų raidžių kiekis:</label><br>
        <input type="number" name="uppercase" value="3" min="0" required><br><br>

        <label>Skaičių kiekis:</label><br>
        <input type="number" name="numbers" value="3" min="0" required><br><br>

        <label>Specialių simbolių kiekis:</label><br>
        <input type="number" name="specials" value="3" min="0" required><br><br>

        <button type="submit" name="generate">Generuoti</button>
    </form>

    <hr>

    <h2>Išsaugoti slaptažodį</h2>

    <form method="POST">
        <label>Pavadinimas:</label><br>
        <input type="text" name="title" placeholder="Pvz. Gmail"><br><br>

        <label>Slaptažodis:</label><br>
        <input type="text" name="password" value="<?php echo htmlspecialchars($generatedPassword); ?>"><br><br>

        <button type="submit" name="save">Išsaugoti</button>
    </form>

    <br>
    <a href="dashboard.php">Grįžti į valdymo skydelį</a>

</body>
</html>