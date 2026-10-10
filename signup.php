
<?php

$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$success = false;
$name = '';
$age = '';
$city = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $city = trim($_POST['city'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required';
    }

    if ($city === '') {
        $errors[] = 'City is required';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO users (name, age, city)
             VALUES (:name, :age, :city)'
        );

        $stmt->execute([
            ':name' => $name,
            ':age' => ($age === '') ? null : (int) $age,
            ':city' => $city
        ]);

        $success = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">

        <div class="icon">✦</div>

        <h1>Create Account</h1>
        <p class="subtitle">Enter your details to get started.</p>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your name"
                value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
            >

            <label for="age">Age</label>
            <input
                type="number"
                id="age"
                name="age"
                min="0"
                placeholder="Enter your age"
                value="<?php echo htmlspecialchars($age, ENT_QUOTES, 'UTF-8'); ?>"
            >

            <label for="city">City</label>
            <input
                type="text"
                id="city"
                name="city"
                placeholder="Enter your city"
                value="<?php echo htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?>"
            >

            <button type="submit">Create Account</button>

        </form>

        <?php if ($success): ?>
            <div class="success">
                <?php
                echo 'Welcome '
                    . htmlspecialchars($name, ENT_QUOTES, 'UTF-8')
                    . ' from '
                    . htmlspecialchars($city, ENT_QUOTES, 'UTF-8')
                    . '!';
                ?>
            </div>
        <?php endif; ?>

        <p class="footer">Your information is securely submitted.</p>

    </div>

</body>
</html>
