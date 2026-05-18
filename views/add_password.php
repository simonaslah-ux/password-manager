<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/PasswordGenerator.php';

$generatedPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length = (int) $_POST['length'];
    $lowercase = (int) $_POST['lowercase'];
    $uppercase = (int) $_POST['uppercase'];
    $numbers = (int) $_POST['numbers'];
    $specials = (int) $_POST['specials'];

    if ($lowercase + $uppercase + $numbers + $specials <= $length) {
        $generator = new PasswordGenerator($length, $lowercase, $uppercase, $numbers, $specials);
        $generatedPassword = $generator->generate();
    } else {
        $generatedPassword = 'Klaida: simbolių kiekis negali būti didesnis už bendrą ilgį.';
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Slaptažodžio generavimas</title>
</head>
<body>

    <h1>Slaptažodžio generavimas</h1>

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

        <button type="submit">Generuoti</button>
    </form>

    <?php if (!empty($generatedPassword)): ?>
        <h3>Sugeneruotas slaptažodis:</h3>
        <p><strong><?php echo htmlspecialchars($generatedPassword); ?></strong></p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Grįžti į valdymo skydelį</a>

</body>
</html>