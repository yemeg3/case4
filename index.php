<!DOCTYPE html>
<html>
<head>
    <title>Управление проектами</title>
    <style>
        form {
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .in-progress {
            background-color: #aaffaa;
        }
        .completed {
            background-color: #aaaaff;
        }
        .planned {
            background-color: #ffffaa;
        }
    </style>
</head>
<body>

<h1>Управление проектами</h1>

<h2>Создание пользователя</h2>

<?php
$servername = "localhost";
$username = "c90827ax_root";
$password = "vostcorp12Qaq";
$dbname = "c90827ax_root";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_username"])) {
    $create_username = $_POST["create_username"];
    $create_password = $_POST["create_password"];


    $hashed_password = password_hash($create_password, PASSWORD_DEFAULT);


    $insert_sql = "INSERT INTO users (username, password) VALUES ('$create_username', '$hashed_password')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "Пользователь успешно создан.";
    } else {
        echo "Ошибка при создании пользователя: " . $conn->error;
    }
}


$conn->close();
?>

<form method="post" action="">
    <label for="create_username">Имя пользователя:</label>
    <input type="text" name="create_username" required><br>
    <label for="create_password">Пароль:</label>
    <input type="password" name="create_password" required><br>
    <button type="submit">Создать пользователя</button>
</form>

<h2>Вход в систему</h2>

<?php
$servername = "localhost";
$username = "c90827ax_root";
$password = "vostcorp12Qaq";
$dbname = "c90827ax_root";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login_username"])) {
    $login_username = $_POST["login_username"];
    $login_password = $_POST["login_password"];


    $select_sql = "SELECT * FROM users WHERE username='$login_username'";
    $result = $conn->query($select_sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($login_password, $user["password"])) {
            session_start();
            $_SESSION["username"] = $user["username"];
            exit("<meta http-equiv='refresh' content='0; url= /user.php'>");
        } else {
            echo "Неверный пароль.";
        }
    } else {
        echo "Пользователь не найден.";
    }
}


$conn->close();
?>

<form method="post" action="">
    <label for="login_username">Имя пользователя:</label>
    <input type="text" name="login_username" required><br>
    <label for="login_password">Пароль:</label>
    <input type="password" name="login_password" required><br>
    <button type="submit">Войти</button>
</form>

</body>
</html>
