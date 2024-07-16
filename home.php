<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_email'])) {
    $db = new SQLite3('users.db');
    $new_email = $_POST['email'];
    $username = $_SESSION['username'];

    $stmt = $db->prepare('UPDATE users SET email = :email WHERE username = :username');
    $stmt->bindValue(':email', $new_email, SQLITE3_TEXT);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $_SESSION['email'] = $new_email;
    }

    $db->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_note'])) {
    $db = new SQLite3('users.db');
    $note = $_POST['note'];
    $username = $_SESSION['username'];

    $stmt = $db->prepare('INSERT INTO notes (username, note) VALUES (:username, :note)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':note', $note, SQLITE3_TEXT);
    $stmt->execute();
    $db->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <form method="post" action="">
            <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
            <input type="submit" name="update_email" value="Update Email">
        </form>
        <h2>Notes</h2>
        <form method="post" action="">
            <textarea name="note" placeholder="Add a note" required></textarea>
            <input type="submit" name="add_note" value="Add Note">
        </form>
        <h2>Your Notes</h2>
        <ul>
            <?php
            $db = new SQLite3('users.db');
            $username = $_SESSION['username'];
            $result = $db->query("SELECT note FROM notes WHERE username = '$username'");
            while ($row = $result->fetchArray()) {
                echo "<li>" . htmlspecialchars($row['note']) . "</li>";
            }
            $db->close();
            ?>
        </ul>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
