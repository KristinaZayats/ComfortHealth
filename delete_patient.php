<?php
require_once("config.php");

$id = null;
$patient = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM patient WHERE PatientID = $id";
    $result = mysqli_query($conn, $sql);
    $patient = mysqli_fetch_assoc($result);
}

if (isset($_POST['confirm_delete'])) {
    if (!$id) {
        echo "Ошибка: пациент не выбран!";
    } else {
        // Check if patient exists
        $sql = "SELECT COUNT(*) AS num_patients FROM patient WHERE PatientID = $id";
        $result = mysqli_query($conn, $sql);
        $num_patients = mysqli_fetch_assoc($result)['num_patients'];
        if ($num_patients == 0) {
            echo "Ошибка: пациент не найден!";
        } else {
            // Check if patient has any appointments
            $sql = "SELECT COUNT(*) AS num_appointments FROM appointment WHERE PatientID = $id";
            $result = mysqli_query($conn, $sql);
            $num_appointments = mysqli_fetch_assoc($result)['num_appointments'];
            if ($num_appointments > 0) {
                echo "Нельзя удалить пациента, у которого есть записи на прием!";
            } else {
                $sql = "DELETE FROM patient WHERE PatientID = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "Пациент успешно удален!";
                    $id = null;
                    $patient = null;
                } else {
                    echo "Ошибка удаления пациента: " . mysqli_error($conn);
                }
            }
        }
    }
}

$sql = "SELECT PatientID, Fullname FROM patient";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Удаление данных пациента</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <h2>Удаление данных пациента</h2>
</header>
<main>
    <form method="post">
        <label for="id">Выберите пациента:</label>
        <select name="id" id="id">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['PatientID'] ?>" <?php echo $row['PatientID'] == $id ? 'selected' : '' ?>><?php echo $row['PatientID'] . " " . $row['Fullname'] ?></option>
            <?php } ?>
        </select>
        <br><br>
        <?php if ($patient) { ?>
            <p>Вы уверены, что хотите удалить следующего пациента?</p>
            <p>ID: <?php echo $patient['PatientID'] ?></p>
            <p>ФИО: <?php echo $patient['Fullname'] ?></p>
            <p>Дата рождения: <?php echo $patient['Birthdate'] ?></p>
            <p>Пол: <?php echo $patient['Sex'] ?></p>
            <p>Адрес: <?php echo $patient['Address'] ?></p>
            <p>Электронная почта: <?php echo $patient['Email'] ?></p>
            <p>Телефон: <?php echo $patient['PhoneNumber'] ?></p>
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="submit" name="confirm_delete" value="Подтвердить">
            <script>
                document.getElementById('id').disabled = true;
            </script>
        <?php } else { ?>
        <input type="submit" name="delete_patient" value="Удалить">
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