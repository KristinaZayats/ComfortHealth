<?php
    include 'config.php';

    // проверяем, была ли отправлена форма на обновление записи
    if (isset($_POST['confirm'])) {
        $appointmentID = $_POST['appointmentID'];
        $AppointmentTime = $_POST['AppointmentTime'];
        $AppointmentDate = $_POST['AppointmentDate'];
        $Availability = $_POST['Availability'];
        $DoctorID = $_POST['DoctorID'];
        $PatientID = $_POST['PatientID'];

        // проверяем существование введенного значения врача в базе данных
        $doctorSql = "SELECT * FROM doctor WHERE DoctorID = '$DoctorID'";
        $doctorResult = mysqli_query($conn, $doctorSql);

        if (mysqli_num_rows($doctorResult) > 0) {
            // обновляем данные записи в базе данных
            $sql = "UPDATE appointment SET AppointmentTime='$AppointmentTime', AppointmentDate='$AppointmentDate', Availability='$Availability',  
                DoctorID='$DoctorID'";

            // проверяем, был ли введен пациент
            if (!empty($PatientID)) {
                // проверяем существование введенного значения пациента в базе данных
                $patientSql = "SELECT * FROM patient WHERE PatientID = '$PatientID'";
                $patientResult = mysqli_query($conn, $patientSql);

                if (mysqli_num_rows($patientResult) > 0) {
                    $sql .= ", PatientID='$PatientID'";
                } else {
                    echo "Пациент с указанным ID не существует";
                    return;
                }
            }

            $sql .= " WHERE appointmentID='$appointmentID'";

            if (mysqli_query($conn, $sql)) {
                echo "Данные записи успешно обновлены";
            } else {
                echo "Ошибка обновления данных записи: " . mysqli_error($conn);
            }
        } else {
            echo "Врач с указанным ID не существует";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Обновление данных записи</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Обновление данных записи</h1>
    </header>
    <main>
        <form method="post">
            <label>Выберите запись для обновления:</label>
            <select name="appointmentID" required>
                <?php
                    // Получаем список записей из базы данных
                    $sql = "SELECT appointmentID, AppointmentTime, AppointmentDate FROM appointment";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['appointmentID'] . "'>" . $row['appointmentID'] . " - " . $row['AppointmentDate'] . " " . $row['AppointmentTime'] . "</option>";
                    }
                ?>
            </select>
            <br><br><input type="submit" name="submit" value="Найти">
        </form>

        <?php
            // проверяем, был выбран врач и найден ли он в базе данных
            if (isset($_POST['submit'])) {
                $appointmentID = $_POST['appointmentID'];
                $sql = "SELECT * FROM appointment WHERE appointmentID ='$appointmentID'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
        ?>
        <form method="post">
            <input type="hidden" name="appointmentID" value="<?php echo $row['AppointmentID']; ?>">
            <br><label>Время приёма:</label>
            <input type="time" name="AppointmentTime" value="<?php echo $row['AppointmentTime']; ?>" required>
            <br><br><label>Дата приёма:</label>
            <input type="date" name="AppointmentDate" value="<?php echo $row['AppointmentDate']; ?>" required>
            <br><br><label>Доступность:</label>
            <input type="text" name="Availability" value="<?php echo $row['Availability']; ?>" required>
            <br><br><label>Пациент:</label>
            <input type="text" name="PatientID" value="<?php echo $row['PatientID']; ?>">
            <br><br><label>Врач:</label>
            <input type="text" name="DoctorID" value="<?php echo $row['DoctorID']; ?>" required>
            <br><br><input type="submit" name="confirm" value="Обновить данные">
        </form>
    </main>
    <?php
                } else {
                    echo "Запись с таким ID не найдена в базе данных";
                }
            }
    ?>
</body>
</html>