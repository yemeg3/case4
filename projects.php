<?php
$servername = "localhost";
$username = "c90827ax_root";
$password = "vostcorp12Qaq";
$dbname = "c90827ax_root";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Все задачи</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .task-block {
            padding: 10px;
            margin: 10px 0;
        }
        .status-yellow {
            background-color: #ffeeba; /* Нежно-желтый */
        }
        .status-green {
            background-color: #d4edda; /* Нежно-зеленый */
            color: #155724;
        }
        .status-blue {
            background-color: #d1ecf1; /* Нежно-голубой */
            color: #0c5460;
        }
    </style>
</head>
<body>

<h1>Все задачи</h1>

<?php

$sql = "SELECT projects.id, projects.name AS project_name, stages.name AS stage_name,
               projects.status AS project_status, projects.start_date, projects.end_date, users.username
        FROM projects
        INNER JOIN stages ON projects.id = stages.project_id
        INNER JOIN users ON projects.user_id = users.id
        ORDER BY projects.id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $current_project_id = null;

    while ($row = $result->fetch_assoc()) {
        if ($current_project_id !== $row["id"]) {
            if ($current_project_id !== null) {
                echo "</ul></div>";
            }

            $current_project_id = $row["id"];
            $status_color_class = "";

            if ($row["project_status"] === "В работе") {
                $status_color_class = "status-yellow";
            } elseif ($row["project_status"] === "Завершенная") {
                $status_color_class = "status-green";
            } elseif ($row["project_status"] === "Планируемая") {
                $status_color_class = "status-blue";
            }

            echo "<div class='task-block $status_color_class'>";
            echo "<h2>" . $row["project_name"] . "</h2>";
            echo "<p><strong>Статус:</strong> " . $row["project_status"] . "</p>";
            echo "<p><strong>Дата начала:</strong> " . $row["start_date"] . "</p>";
            echo "<p><strong>Дата завершения:</strong> " . $row["end_date"] . "</p>";
            echo "<p><strong>Пользователь:</strong> " . $row["username"] . "</p>";
            echo "<ul><li>" . $row["stage_name"] . "</li>";
        } else {
            echo "<li>" . $row["stage_name"] . "</li>";
        }
    }

    echo "</ul></div>";
} else {
    echo "Нет доступных задач.";
}


$conn->close();
?>

<a href="user.php">На главную</a>

</body>
</html>
