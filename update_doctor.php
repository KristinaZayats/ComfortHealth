<?php
    include 'config.php';

    // проверяем, была ли отправлена форма на обновление врача
    if (isset($_POST['confirm'])) {
        $doctorID = $_POST['doctorID'];
        $fullname = $_POST['fullname'];
        $speciality = $_POST['speciality'];
        $roomNumber = $_POST['roomNumber'];
        $phoneNumber = $_POST['phoneNumber'];
        $email = $_POST['email'];
        $hospitalID = $_POST['hospitalID'];

        // обновляем данные врача в базе данных
        $sql = "UPDATE doctor SET Fullname='$fullname', Speciality='$speciality', RoomNumber='$roomNumber',  
                PhoneNumber='$phoneNumber', Email='$email', HospitalID='$hospitalID' WHERE DoctorID='$doctorID'";
        if (mysqli_query($conn, $sql)) {
            echo "Данные врача успешно обновлены";
        } else {
            echo "Ошибка обновления данных врача: " . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Обновление данных врача</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Обновление данных врача</h1>
    </header>
    <main>
        <form method="post">
            <label>Выберите врача для обновления:</label>
            <select name="doctorID" required>
                <?php
                    // Получаем список врачей из базы данных
                    $sql = "SELECT DoctorID, Fullname FROM doctor";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['DoctorID'] . "'>" . $row['DoctorID'] . " - " . $row['Fullname'] . "</option>";
                    }
                ?>
            </select>
            <br><br><input type="submit" name="submit" value="Найти">
        </form>

        <?php
            // проверяем, был выбран врач и найден ли он в базе данных
            if (isset($_POST['submit'])) {
                $doctorID = $_POST['doctorID'];
                $sql = "SELECT * FROM doctor WHERE DoctorID='$doctorID'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
        ?>

        <form method="post">
            <input type="hidden" name="doctorID" value="<?php echo $row['DoctorID']; ?>">
            <br><label>ФИО:</label>
            <input type="text" name="fullname" value="<?php echo $row['Fullname']; ?>" required>
            <br><br><label>Специальность:</label>
            <input type="text" name="speciality" value="<?php echo $row['Speciality']; ?>" required>
            <br><br><label>Номер кабинета:</label>
            <input type="text" name="roomNumber" value="<?php echo $row['RoomNumber']; ?>" required>
            <br><br><label>Номер телефона:</label>
            <input type="text" name="phoneNumber" value="<?php echo $row['PhoneNumber']; ?>" required>
            <br><br><label>Email:</label>
            <input type="email" name="email" value="<?php echo $row['Email']; ?>" required>
            <br><br><label>ID больницы:</label>
            <input type="text" name="hospitalID" value="<?php echo $row['HospitalID']; ?>" required>
            <br><br><input type="submit" name="confirm" value="Обновить данные">
        </form>

    </main>
    <?php
            } else {
                echo "Врача с таким ID не найдено в базе данных";
            }
        }
    ?>
</body>
</html>