<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

$servername = "localhost";
$username = "c90827ax_root";
$password = "vostcorp12Qaq";
$dbname = "c90827ax_root";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


$user_list_query = "SELECT username FROM users";
$user_list_result = $conn->query($user_list_query);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user1 = $_POST["user1"];
    $user2 = $_POST["user2"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];


    $user1_tasks_query = "SELECT projects.name AS task_name, stages.name AS stage_name, projects.start_date, projects.end_date
                          FROM projects
                          INNER JOIN stages ON projects.id = stages.project_id
                          WHERE projects.user_id = (SELECT id FROM users WHERE username='$user1')
                          AND projects.end_date BETWEEN '$start_date' AND '$end_date'";

    $user2_tasks_query = "SELECT projects.name AS task_name, stages.name AS stage_name, projects.start_date, projects.end_date
                          FROM projects
                          INNER JOIN stages ON projects.id = stages.project_id
                          WHERE projects.user_id = (SELECT id FROM users WHERE username='$user2')
                          AND projects.end_date BETWEEN '$start_date' AND '$end_date'";

    $user1_tasks_result = $conn->query($user1_tasks_query);
    $user2_tasks_result = $conn->query($user2_tasks_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Сравнение активности</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<h1>Сравнение активности пользователей</h1>

<form method="post" action="">
    <label for="user1">Выберите первого пользователя:</label>
    <select name="user1" required>
        <?php
        while ($user_row = $user_list_result->fetch_assoc()) {
            echo "<option value='" . $user_row["username"] . "'>" . $user_row["username"] . "</option>";
        }
        ?>
    </select><br>

    <label for="user2">Выберите второго пользователя:</label>
    <select name="user2" required>
        <?php
        mysqli_data_seek($user_list_result, 0);
        while ($user_row = $user_list_result->fetch_assoc()) {
            echo "<option value='" . $user_row["username"] . "'>" . $user_row["username"] . "</option>";
        }
        ?>
    </select><br>

    <label for="start_date">Выберите начальную дату:</label>
    <input type="date" name="start_date" required><br>

    <label for="end_date">Выберите конечную дату:</label>
    <input type="date" name="end_date" required><br>

    <button type="submit">Сравнить</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "<h2>Задачи пользователя $user1</h2>";
    if ($user1_tasks_result->num_rows > 0) {
        echo "<ul>";
        while ($task_row = $user1_tasks_result->fetch_assoc()) {
            echo "<li>" . $task_row["task_name"] . " - " . $task_row["stage_name"] . " ("
                . "Дата начала: " . $task_row["start_date"] . ", "
                . "Дата завершения: " . $task_row["end_date"] . ")"
                . "</li>";
        }
        echo "</ul>";
    } else {
        echo "У пользователя $user1 нет выполненных задач за указанный период.";
    }

    echo "<h2>Задачи пользователя $user2</h2>";
    if ($user2_tasks_result->num_rows > 0) {
        echo "<ul>";
        while ($task_row = $user2_tasks_result->fetch_assoc()) {
            echo "<li>" . $task_row["task_name"] . " - " . $task_row["stage_name"] . " ("
                . "Дата начала: " . $task_row["start_date"] . ", "
                . "Дата завершения: " . $task_row["end_date"] . ")"
                . "</li>";
        }
        echo "</ul>";
    } else {
        echo "У пользователя $user2 нет выполненных задач за указанный период.";
    }

    echo "<h2>Итог сравнения:</h2>";
    echo "<p>Пользователь $user1 выполнил " . $user1_tasks_result->num_rows . " задач(и) за $start_date - $end_date</p>";
    echo "<p>Пользователь $user2 выполнил " . $user2_tasks_result->num_rows . " задач(и) за $start_date - $end_date</p>";
}
?>

<br><a href="user.php">На главную</a></br>

</body>
</html>
