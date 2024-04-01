<!DOCTYPE html>
<html>
<head>
    <title>Соотношение занятых талонов врача к незанятым</title>
    <link rel="stylesheet" href="style.css">
    <style>
        canvas {
            width: 400px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Соотношение занятых талонов врача к незанятым</h1>
        </header>
        <main>
            <form action="" method="post">
                <label for="DoctorID">Выберите врача:</label>
                <select name="DoctorID" id="DoctorID">
                    <?php
                        require_once 'config.php';

                        // Получаем список врачей из базы данных
                        $sql = "SELECT DoctorID, FullName FROM doctor";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['DoctorID'] . "'>" . $row['FullName'] . "</option>";
                        }
                    ?>
                </select>
                <br><br>
                <input type="submit" name="submit" value="Выбрать">
            </form>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DoctorID'])) {
                    $DoctorID = $_POST['DoctorID'];

                    // Получаем количество занятых талонов
                    $occupiedSql = "SELECT COUNT(*) AS occupied_count FROM appointment WHERE DoctorID = '$DoctorID' AND Availability = 'No'";
                    $occupiedResult = $conn->query($occupiedSql);
                    $occupiedCount = $occupiedResult->fetch_assoc()['occupied_count'];

                    // Получаем количество свободных талонов
                    $totalSql = "SELECT COUNT(*) AS total_count FROM appointment WHERE DoctorID = '$DoctorID'";
                    $totalResult = $conn->query($totalSql);
                    $totalCount = $totalResult->fetch_assoc()['total_count'];

                    // Закрываем соединение с базой данных
                    $conn->close();

                    // Создаем круговую диаграмму
                    echo "<canvas id='chart'></canvas>";
                    echo "<script>
                            var canvas = document.getElementById('chart');
                            var ctx = canvas.getContext('2d');
                            
                            var occupiedCount = $occupiedCount;
                            var totalCount = $totalCount;
                            var freeCount = totalCount - occupiedCount;
                            var occupiedPercentage = (occupiedCount / totalCount) * 100;
                            var freePercentage = 100 - occupiedPercentage;
                            
                            var radius = Math.min(canvas.width, canvas.height) / 2;
                            var centerX = canvas.width / 2;
                            var centerY = canvas.height / 2;
                            
                            ctx.beginPath();
                            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                            ctx.fillStyle = '#9370DB';
                            ctx.fill();
                            
                            ctx.beginPath();
                            ctx.moveTo(centerX, centerY);
                            ctx.arc(centerX, centerY, radius, 0, (2 * Math.PI * occupiedPercentage) / 100);
                            ctx.fillStyle = '#483D8B';
                            ctx.fill();
                            
                            ctx.font = 'bold 16px Arial';
                            ctx.fillStyle = '#ffffff';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText('Занято: ' + occupiedCount, centerX, centerY - 10);
                            
                            ctx.font = 'bold 16px Arial';
                            ctx.fillText('Свободно: ' + freeCount, centerX, centerY + 20);
                        </script>";
                }
            ?>
        </main>
    </div>
</body>
</html>
