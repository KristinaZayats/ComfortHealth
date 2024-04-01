<?php
require_once("config.php");

$id = null;
$hospital = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM hospital WHERE HospitalID = $id";
    $result = mysqli_query($conn, $sql);
    $hospital = mysqli_fetch_assoc($result);
}

if (isset($_POST['confirm_delete'])) {
    if (!$id) {
        echo "Ошибка: поликлиника не выбрана!";
    } else {
        $sql = "SELECT COUNT(*) AS num_hospitals FROM hospital WHERE HospitalID = $id";
        $result = mysqli_query($conn, $sql);
        $num_hospitals = mysqli_fetch_assoc($result)['num_hospitals'];
        if ($num_hospitals == 0) {
            echo "Ошибка: поликлиника не найдена!";
        } else {
            $sql = "SELECT COUNT(*) AS num_doctors FROM doctor WHERE HospitalID = $id";
            $result = mysqli_query($conn, $sql);
            $num_doctors = mysqli_fetch_assoc($result)['num_doctors'];
            if ($num_doctors > 0) {
                echo "Нельзя удалить поликлинику, в которой работает хотя бы один врач!";
            } else {
                $sql = "DELETE FROM hospital WHERE HospitalID = $id";
                if (mysqli_query($conn, $sql)) {
                    echo "Поликлиника успешно удалена!";
                    $id = null;
                    $hospital = null;
                } else {
                    echo "Ошибка удаления поликлиники: " . mysqli_error($conn);
                }
            }
        }
    }
}

$sql = "SELECT HospitalID, Name FROM hospital";
$result = mysqli_query($conn, $sql);

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Удаление данных поликлиники</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
    <header>
        <h2>Удаление данных поликлиники</h2>
    </header>
    <main>
        <form method="post">
            <label for="id">Выберите поликлинику:</label>
            <select name="id" id="id">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['HospitalID'] ?>" <?php echo $row['HospitalID'] == $id ? 'selected' : '' ?>><?php echo $row['HospitalID'] . " " . $row['Name'] ?></option>
                <?php } ?>
            </select>
            <br><br>
            <?php if ($hospital) { ?>
                <p>Вы уверены, что хотите удалить следующую поликлинику?</p>
                <p>ID: <?php echo $hospital['HospitalID'] ?></p>
                <p>Название: <?php echo $hospital['Name'] ?></p>
                <p>Адрес: <?php echo $hospital['Address'] ?></p>
                <p>Часы работы: <?php echo $hospital['WorkingHours'] ?></p>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="submit" name="confirm_delete" value="Подтвердить">
                <script>
                    document.getElementById('id').disabled = true;
                </script>
            <?php } else { ?>
            <input type="submit" name="delete_hospital" value="Удалить">
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