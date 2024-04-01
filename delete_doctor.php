<?php
require_once("config.php");

$id = null;
$doctor = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM doctor WHERE DoctorID = $id";
    $result = mysqli_query($conn, $sql);
    $doctor = mysqli_fetch_assoc($result);
}

if (isset($_POST['confirm_delete'])) {
    if (!$id) {
        echo "Ошибка: врач не выбран!";
    } else {
        $sql = "SELECT COUNT(*) AS num_doctors FROM doctor WHERE DoctorID = $id";
        $result = mysqli_query($conn, $sql);
        $num_doctors = mysqli_fetch_assoc($result)['num_doctors'];
        if ($num_doctors == 0) {
            echo "Ошибка: врач не найден!";
        } else {
            $sql = "SELECT COUNT(*) AS num_appointments FROM appointment WHERE DoctorID = $id";
            $result = mysqli_query($conn, $sql);
            $num_appointments = mysqli_fetch_assoc($result)['num_appointments'];
            if ($num_appointments > 0) {
                echo "Нельзя удалить врача, у которого есть записи на прием!";
            } else {
                $sql = "DELETE FROM doctor WHERE DoctorID = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "Врач успешно удален!";
                    $id = null;
                    $doctor = null;
                } else {
                    echo "Ошибка удаления врача: " . mysqli_error($conn);
                }
            }
        }
    }
}

$sql = "SELECT DoctorID, Fullname FROM doctor";
$result = mysqli_query($conn, $sql);

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Удаление данных врача</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <header>
        <h2>Удаление данных врача</h2>
    </header>
    <main>
        <form method="post">
            <label for="id">Выберите врача:</label>
            <select name="id" id="id">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['DoctorID'] ?>" <?php echo $row['DoctorID'] == $id ? 'selected' : '' ?>><?php echo $row['DoctorID'] . " " . $row['Fullname'] ?></option>
                <?php } ?>
            </select>
            <br><br>
            <?php if ($doctor) { ?>
                <p>Вы уверены, что хотите удалить следующего врача?</p>
                <p>ID: <?php echo $doctor['DoctorID'] ?></p>
                <p>ФИО: <?php echo $doctor['Fullname'] ?></p>
                <p>Специализация: <?php echo $doctor['Speciality'] ?></p>
                <p>Номер кабинета: <?php echo $doctor['RoomNumber'] ?></p>
                <p>Электронная почта: <?php echo $doctor['Email'] ?></p>
                <p>Телефон: <?php echo $doctor['PhoneNumber'] ?></p>
                <p>ID поликлиники: <?php echo $doctor['HospitalID'] ?></p>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="submit" name="confirm_delete" value="Подтвердить">
                <script>
                    document.getElementById('id').disabled = true;
                </script>
            <?php } else { ?>
            <input type="submit" name="delete_doctor" value="Удалить">
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