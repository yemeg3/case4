<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $servername = "localhost";
  $username = "c90827ax_root";
  $password = "vostcorp12Qaq";
  $dbname = "c90827ax_root";




    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $task_name = $_POST["task_name"];
    $user_username = $_SESSION["username"];


    $user_id_query = "SELECT id FROM users WHERE username='$user_username'";
    $user_id_result = $conn->query($user_id_query);

    if ($user_id_result->num_rows > 0) {
        $user_id_row = $user_id_result->fetch_assoc();
        $user_id = $user_id_row["id"];


        $insert_sql = "INSERT INTO projects (name, user_id) VALUES ('$task_name', '$user_id')";

        if ($conn->query($insert_sql) === TRUE) {
            $project_id = $conn->insert_id;

            foreach ($_POST as $key => $value) {
                if (strpos($key, "stage_") === 0) {
                    $stage_name = $value;
                    $insert_stage_sql = "INSERT INTO stages (project_id, name) VALUES ('$project_id', '$stage_name')";
                    $conn->query($insert_stage_sql);
                }
            }

            echo "Задача успешно создана.";
        } else {
            echo "Ошибка при создании задачи: " . $conn->error;
        }
    } else {
        echo "Пользователь не найден.";
    }


    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Создание задачи</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        var stageCount = 1;

        function addStage() {
            stageCount++;

            var stageContainer = document.getElementById("stage_container");
            var newStageInput = document.createElement("input");
            newStageInput.setAttribute("type", "text");
            newStageInput.setAttribute("name", "stage_" + stageCount);
            newStageInput.setAttribute("placeholder", "Этап №" + stageCount);
            newStageInput.setAttribute("required", "true");
            stageContainer.appendChild(newStageInput);
            stageContainer.appendChild(document.createElement("br"));
        }
    </script>
</head>
<body>

<h1>Создание задачи</h1>

<form method="post" action="">
    <label for="task_name">Название задачи:</label>
    <input type="text" name="task_name" required><br>
    <div id="stage_container">
        <input type="text" name="stage_1" placeholder="Этап №1" required>
        <br>
    </div>
    <button type="button" onclick="addStage()">Добавить этап</button>
    <br>
    <button type="submit">Создать задачу</button>
</form>

</body>
<a href="user.php">На главную</a>
</html>
