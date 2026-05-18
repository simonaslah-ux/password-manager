<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/PasswordEntry.php';

$passwordEntry = new PasswordEntry();

$passwords = $passwordEntry->getUserPasswords(
    $_SESSION['user_id'],
    $_SESSION['plain_password']
);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Išsaugoti slaptažodžiai</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <h1>Išsaugoti slaptažodžiai</h1>

    <?php if (empty($passwords)): ?>
        <p>Slaptažodžių dar nėra.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Pavadinimas</th>
                <th>Slaptažodis</th>
                <th>Sukurta</th>
            </tr>

            <?php foreach ($passwords as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['decrypted_password']); ?></td>
                    <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="add_password.php">Pridėti naują slaptažodį</a><br>
    <a href="dashboard.php">Grįžti į valdymo skydelį</a>

</body>
</html>