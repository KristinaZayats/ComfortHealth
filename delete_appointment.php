<?php
require_once("config.php");

$id = null;
$appointment = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM appointment WHERE AppointmentID = $id";
    $result = mysqli_query($conn, $sql);
    $appointment = mysqli_fetch_assoc($result);
}

if (isset($_POST['confirm_delete'])) {
    if (!$id) {
        echo "Ошибка: запись не выбрана!";
    } else {
        $sql = "SELECT COUNT(*) AS num_appointments FROM appointment WHERE AppointmentID = $id";
        $result = mysqli_query($conn, $sql);
        $num_appointments = mysqli_fetch_assoc($result)['num_appointments'];
        if ($num_appointments == 0) {
            echo "Ошибка: запись не найдена!";
        } else {
                $sql = "DELETE FROM appointment WHERE AppointmentID = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "Запись успешно удалена!";
                    $id = null;
                    $appointment = null;
                } else {
                    echo "Ошибка удаления записи: " . mysqli_error($conn);
                }
        }
    }
}

$sql = "SELECT AppointmentID, AppointmentDate, AppointmentTime FROM appointment ORDER BY AppointmentDate";
$result = mysqli_query($conn, $sql);

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Удаление данных о записи</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <header>
        <h2>Удаление данных о записи</h2>
    </header>
    <main>
        <form method="post">
            <label for="id">Выберите запись:</label>
            <select name="id" id="id">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['AppointmentID'] ?>" <?php echo $row['AppointmentID'] == $id ? 'selected' : '' ?>><?php echo $row['AppointmentID'] . " " . $row['AppointmentDate'] . " " . $row['AppointmentTime'] ?></option>
                <?php } ?>
            </select>
            <br><br>
            <?php if ($appointment) { ?>
                <p>Вы уверены, что хотите удалить следующую запись?</p>
                <p>ID: <?php echo $appointment['AppointmentID'] ?></p>
                <p>Дата: <?php echo $appointment['AppointmentDate'] ?></p>
                <p>Время: <?php echo $appointment['AppointmentTime'] ?></p>
                <p>Доступность: <?php echo $appointment['Availability'] ?></p>
                <p>ID врача: <?php echo $appointment['DoctorID'] ?></p>
                <p>ID пациента: <?php echo $appointment['PatientID'] ?></p>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="submit" name="confirm_delete" value="Подтвердить">
                <script>
                    document.getElementById('id').disabled = true;
                </script>
            <?php } else { ?>
            <input type="submit" name="delete_appointment" value="Удалить">
            <?php } ?>
        </form>

        <br>
        <a href="delete.php">Вернуться к выбору таблицы для удаления</a>
    </main>
    </body>
    </html>
<?php
mysqli_close($conn);
?>