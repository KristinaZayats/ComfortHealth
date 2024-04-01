<?php
    include 'config.php';

    // проверяем, была ли отправлена форма на обновление поликлиники
    if (isset($_POST['confirm'])) {
        $HospitalID = $_POST['hospitalID'];
        $Name = $_POST['Name'];
        $Address = $_POST['Address'];
        $WorkingHours = $_POST['WorkingHours'];

        // обновляем данные о поликлинике в базе данных
        $sql = "UPDATE hospital SET Name='$Name', Address='$Address', WorkingHours='$WorkingHours' WHERE HospitalID='$HospitalID'";
        if (mysqli_query($conn, $sql)) {
            echo "Данные о поликлинике успешно обновлены";
        } else {
            echo "Ошибка обновления данных о поликлинике: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Обновление данных о поликлинике</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Обновление данных о поликлинике</h1>
    </header>
    <main>
        <form method="post">
            <label>Выберите поликлинику для обновления:</label>
            <select name="hospitalID" required>
                <?php
                    // Получаем список поликлиник из базы данных
                    $sql = "SELECT HospitalID, Name FROM hospital";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['HospitalID'] . "'>" . $row['HospitalID'] . " - " . $row['Name'] . "</option>";
                    }
                ?>
            </select>
            <br><br><input type="submit" name="submit" value="Найти">
        </form>

        <?php
            // проверяем, была выбрана поликлиника и найдена ли она в базе данных
            if (isset($_POST['submit'])) {
                $hospitalID = $_POST['hospitalID'];
                $sql = "SELECT * FROM hospital WHERE HospitalID='$hospitalID'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
        ?>

        <form method="post">
            <input type="hidden" name="hospitalID" value="<?php echo $row['HospitalID']; ?>">
            <br><label>Название:</label>
            <input type="text" name="Name" value="<?php echo $row['Name']; ?>" required>
            <br><br><label>Адрес:</label>
            <input type="text" name="Address" value="<?php echo $row['Address']; ?>" required>
            <br><br><label>Рабочие часы:</label>
            <input type="text" name="WorkingHours" value="<?php echo $row['WorkingHours']; ?>" required>
            <br><br><input type="submit" name="confirm" value="Обновить данные">
        </form>
    </main>
    <?php
            } else {
                echo "Поликлиника с таким ID не найдена в базе данных";
            }
        }
    ?>
</body>
</html>