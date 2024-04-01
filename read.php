<?php
    require_once('config.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Просмотр</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
	<h1>Просмотр данных в каждой таблице</h1>
</header>
<main>
	<form method="POST" action="">
		<label for="table">Выберите таблицу для просмотра данных:</label>
		<select name="table" id="table">
			<option value="patient">Пациент</option>
			<option value="doctor">Врач</option>
			<option value="appointment">Запись</option>
			<option value="hospital">Поликлиника</option>
		</select>
		<br><br>
		<input type="submit" name="submit" value="Выбрать">
	</form>
</main>

	<?php
		if (isset($_POST['submit'])) {
			$table = $_POST['table'];
			$query = "SELECT * FROM " . $table;

			$result = mysqli_query($conn, $query);

			if (mysqli_num_rows($result) > 0) {
				echo "<table border='1'>";
				echo "<tr>";
				while ($fieldinfo = mysqli_fetch_field($result)) {
					$russian_names = array(
        						'PatientID' => 'ID Пациента',
        						'Fullname' => 'ФИО',
        						'Birthdate' => 'Дата рождения',
						'Address' => 'Адрес',
						'PhoneNumber' => 'Номер телефона',
						'InsuranceNumber' => 'Номер полиса',
						'Email' => 'Электронная почта',
						'Sex' => 'Пол',
						'Speciality' => 'Специальность',
						'RoomNumber' => 'Номер кабинета',
						'HospitalID' => 'ID Поликлиники',
						'DoctorID' => 'ID Врача',
						'AppointmentID' => 'ID записи',
						'AppointmentTime' => 'Время записи',
						'AppointmentDate' => 'Дата записи',
						'Availability' => 'Доступность',
						'Name' => 'Название',
						'WorkingHours' => 'Часы работы',
    					);
    					echo "<th>" . $russian_names[$fieldinfo->name] . "</th>";
				}
				echo "</tr>";

				while ($row = mysqli_fetch_assoc($result)) {
					echo "<tr>";
					foreach ($row as $value) {
						echo "<td>" . $value . "</td>";
					}
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "Результатов не найдено.";
			}

			mysqli_free_result($result);
			mysqli_close($conn);
			$conn = null;
		}
	?>
</body>
</html>
