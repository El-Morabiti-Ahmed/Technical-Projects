<?php
require_once "database.php";


if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['country'])){
        $stmt = $pdo->prepare("INSERT INTO gamer(username, email, country) VALUES(:username, :email, :country)");
        $stmt->execute([
            "username" => $_POST['username'],
            "email" => $_POST['email'],
            "country" => $_POST['country']
        ]
        );
        header("Location: " . $_SERVER["PHP_SELF"]);
    }
}


$gamers = $pdo->query("SELECT * FROM gamer")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esports Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Esports Portal</h1>
    <fieldset><legend>Gamer Creation Form</legend>
    <form action="index.php" method="POST">
        <input type="text" name="username" placeholder="username"><br><br>
        <input type="email" name="email" placeholder="email@example.com"><br><br>
        <input type="text" name="country" placeholder="country">
        <button type="submit">Add Gamer</button>
    </form>
    </fieldset><br><br>
    <div class="parent">
    <?php foreach($gamers as $gamer): ?>
        <div class="card">
            Gamer <?= $gamer['id'] ?>:<br><br>
            <span class="t">Username:</span> <span class="v"><?= $gamer['username'] ?> </span><br>
            <span class="t">Email:</span> <span class="v"><?= $gamer['email'] ?> </span><br>
            <span class="t">Country:</span> <span class="v"><?= $gamer['country'] ?> </span>
        </div>
    <?php endforeach ?>
    </div>

</body>
</html>