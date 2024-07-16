<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new SQLite3('users.db');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $message = "Registration successful!";
    } else {
        $message = "Error occurred during registration!";
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if ($message != "") { echo "<div class='message'>$message</div>"; } ?>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="submit" value="Register">
        </form>
        <a href="index.php">Login</a>
    </div>
</body>
</html>
