<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_id = $_POST["task_id"];
    $new_status = $_POST["new_status"];

    $servername = "localhost";
    $username = "c90827ax_root";
    $password = "vostcorp12Qaq";
    $dbname = "c90827ax_root";


    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    if ($new_status === "В работе") {
        $sql_update = "UPDATE projects SET status='$new_status', start_date=NOW(), end_date=NULL WHERE id='$task_id'";
    } elseif ($new_status === "Завершенная") {
        $sql_update = "UPDATE projects SET status='$new_status', end_date=NOW() WHERE id='$task_id'";
    } else {
        $sql_update = "UPDATE projects SET status='$new_status', start_date=NOW(), end_date=NULL, start_date=NULL WHERE id='$task_id'";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "Статус задачи успешно обновлен.";
    } else {
        echo "Ошибка при обновлении статуса задачи: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Мои задачи</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .task-block {
            padding: 10px;
            margin: 10px 0;
        }
        .status-yellow {
            background-color: #ffeeba;
        }
        .status-green {
            background-color: #d4edda;
            color: #155724;
        }
        .status-blue {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>

<h1>Мои задачи</h1>

<?php
$servername = "localhost";
$username = "c90827ax_root";
$password = "vostcorp12Qaq";
$dbname = "c90827ax_root";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$user_username = $_SESSION["username"];


$sql = "SELECT projects.id, projects.name AS project_name, stages.name AS stage_name, projects.status AS project_status
        FROM projects
        INNER JOIN stages ON projects.id = stages.project_id
        WHERE projects.user_id = (SELECT id FROM users WHERE username='$user_username')
        ORDER BY projects.id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $current_project_id = null;



    while ($row = $result->fetch_assoc()) {
        $status_class = "";

        switch ($row["project_status"]) {
          case "В работе":
              $status_class = "status-yellow";
              break;
          case "Завершенная":
              $status_class = "status-green";
              break;
          case "Планируемая":
              $status_class = "status-blue";
              break;
          default:
              break;
        }

        if ($current_project_id !== $row["id"]) {
            if ($current_project_id !== null) {
                echo "</ul><form method='post'>
                          <input type='hidden' name='task_id' value='$current_project_id'>
                          <select name='new_status'>
                            <option value='В работе'>В работе</option>
                            <option value='Завершенная'>Завершенная</option>
                            <option value='Планируемая'>Планируемая</option>
                          </select>
                          <input type='submit' value='Изменить статус'>
                        </form></div>";
            }

            $current_project_id = $row["id"];
            echo "<div class='task-block $status_class'>";
            echo "<h2>" . $row["project_name"] . "</h2>";
            echo "<p><strong>Статус:</strong> " . $row["project_status"] . "</p>";
            echo "<ul><li>" . $row["stage_name"] . "</li>";
        } else {
            echo "<li>" . $row["stage_name"] . "</li>";
        }
    }

    echo "</ul><form method='post'>
              <input type='hidden' name='task_id' value='$current_project_id'>
              <select name='new_status'>
                <option value='В работе'>В работе</option>
                <option value='Завершенная'>Завершенная</option>
                <option value='Планируемая'>Планируемая</option>
              </select>
              <input type='submit' value='Изменить статус'>
            </form></div>";
} else {
    echo "Нет доступных задач.";
}


$conn->close();
?>

<br><a href="user.php">На главную</a></br>

</body>
</html>
