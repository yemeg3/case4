<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Страница пользователя</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<h1>Добро пожаловать, <?php echo $_SESSION["username"]; ?>!</h1>

<a href="create_task.php">Создать задачу</a><br>
<a href="myprojects.php">Мои задачи</a><br>
<a href="compare_activity.php">Сравнение показателей</a><br>
<a href="projects.php">Все задачи</a><br>

<br><a href="logout.php">Выйти</a></br>
</body>
</html>
