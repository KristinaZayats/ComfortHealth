<?php
    include 'config.php';

    // проверяем, была ли отправлена форма на обновление пациента
    if (isset($_POST['confirm'])) {
        $patientID = $_POST['patientID'];
        $fullname = $_POST['fullname'];
        $Birthdate = $_POST['Birthdate'];
        $Address = $_POST['Address'];
        $phoneNumber = $_POST['phoneNumber'];
        $InsuranceNumber = $_POST['InsuranceNumber'];
        $email = $_POST['email'];
        $Sex = $_POST['Sex'];

        // обновляем данные пациента в базе данных
        $sql = "UPDATE patient SET Fullname='$fullname', Birthdate='$Birthdate', Address='$Address',
                PhoneNumber='$phoneNumber', InsuranceNumber='$InsuranceNumber', Email='$email', Sex='$Sex' WHERE PatientID='$patientID'";
        if (mysqli_query($conn, $sql)) {
            echo "Данные пациента успешно обновлены";
        } else {
            echo "Ошибка обновления данных пациента: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Обновление данных пациента</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Обновление данных пациента</h1>
    </header>
    <main>
        <form method="post">
            <label>Выберите пациента для обновления:</label>
            <select name="patientID" required>
                <?php
                    // Получаем список пациентов из базы данных
                    $sql = "SELECT PatientID, Fullname FROM patient";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['PatientID'] . "'>" . $row['PatientID'] . " - " . $row['Fullname'] . "</option>";
                    }
                ?>
            </select>
            <br><br><input type="submit" name="submit" value="Найти">
        </form>

        <?php
            // проверяем, был ли выбран пациент и найден ли он в базе данных
            if (isset($_POST['submit'])) {
                $patientID = $_POST['patientID'];
                $sql = "SELECT * FROM patient WHERE PatientID='$patientID'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
        ?>

        <form method="post">
            <input type="hidden" name="patientID" value="<?php echo $row['PatientID']; ?>">
            <br><label>ФИО:</label>
            <input type="text" name="fullname" value="<?php echo $row['Fullname']; ?>" required>
            <br><br><label>Дата рождения:</label>
            <input type="text" name="Birthdate" value="<?php echo $row['Birthdate']; ?>" required>
            <br><br><label>Адрес:</label>
            <input type="text" name="Address" value="<?php echo $row['Address']; ?>" required>
            <br><br><label>Номер телефона:</label>
            <input type="text" name="phoneNumber" value="<?php echo $row['PhoneNumber']; ?>" required>
            <br><br><label>Номер полиса:</label>
            <input type="text" name="InsuranceNumber" value="<?php echo $row['InsuranceNumber']; ?>" required>
            <br><br><label>Email:</label>
            <input type="email" name="email" value="<?php echo $row['Email']; ?>" required>
            <br><br><label>Пол:</label>
            <input type="text" name="Sex" value="<?php echo $row['Sex']; ?>" required>
            <br><br><input type="submit" name="confirm" value="Обновить данные">
        </form>
    </main>
    <?php
            } else {
                echo "Пациент с таким ID не найден в базе данных";
            }
        }
    ?>
</body>
</html>