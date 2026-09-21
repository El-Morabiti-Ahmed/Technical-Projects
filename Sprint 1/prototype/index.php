<?php
require "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["add_gamer"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $country = $_POST["country"];

        $sql = "INSERT INTO gamer (username, email, country)
                VALUES (:username, :email, :country)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "username" => $username,
            "email" => $email,
            "country" => $country,
        ]);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }

    if (isset($_POST["add_game"])) {
        $name = $_POST["name"];
        $genre = $_POST["genre"];

        $sql = "INSERT INTO game (name, genre)
                VALUES (:name, :genre)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "name" => $name,
            "genre" => $genre,
        ]);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }

    if (isset($_POST["add_platform"])) {
        $name = $_POST["platform_name"];

        $sql = "INSERT INTO platform (name)
                VALUES (:name)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "name" => $name,
        ]);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}

$gamers = $pdo->query("SELECT * FROM gamer")->fetchAll(PDO::FETCH_ASSOC);
$games = $pdo->query("SELECT * FROM game")->fetchAll(PDO::FETCH_ASSOC);
$platforms = $pdo->query("SELECT * FROM platform")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Sports Portal</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>E-Sports Portal</h1>

    <section>
        <h2>Gamers</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Country</th>
            </tr>
            <?php foreach ($gamers as $gamer): ?>
                <tr>
                    <td><?= htmlspecialchars($gamer["id"]) ?></td>
                    <td><?= htmlspecialchars($gamer["username"]) ?></td>
                    <td><?= htmlspecialchars($gamer["email"]) ?></td>
                    <td><?= htmlspecialchars($gamer["country"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Add Gamer</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="country" placeholder="Country" required>
            <button type="submit" name="add_gamer">Add Gamer</button>
        </form>
    </section>

    <section>
        <h2>Games</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Genre</th>
            </tr>
            <?php foreach ($games as $game): ?>
                <tr>
                    <td><?= htmlspecialchars($game["id"]) ?></td>
                    <td><?= htmlspecialchars($game["name"]) ?></td>
                    <td><?= htmlspecialchars($game["genre"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Add Game</h3>
        <form method="POST">
            <input type="text" name="name" placeholder="Game name" required>
            <input type="text" name="genre" placeholder="Genre" required>
            <button type="submit" name="add_game">Add Game</button>
        </form>
    </section>

    <section>
        <h2>Platforms</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
            <?php foreach ($platforms as $platform): ?>
                <tr>
                    <td><?= htmlspecialchars($platform["id"]) ?></td>
                    <td><?= htmlspecialchars($platform["name"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Add Platform</h3>
        <form method="POST">
            <input type="text" name="platform_name" placeholder="Platform name" required>
            <button type="submit" name="add_platform">Add Platform</button>
        </form>
    </section>
</body>
</html>