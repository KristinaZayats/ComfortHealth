<!DOCTYPE html>
<html>
<head>
	<title>Создание записи</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
		<header>
			<h1>Создание записи к врачу</h1>
		</header>
		<main>
			<form action="create_appointment.php" method="post">
				<label for="AppointmentTime">Время приёма:</label>
				<input type="time" name="AppointmentTime" id="AppointmentTime" required>
				<br><br>
				<label for="AppointmentDate">Дата приёма:</label>
				<input type="date" name="AppointmentDate" id="AppointmentDate" required>
				<br><br>
				<label for="Availability">Доступность:</label>
				<select name="Availability" id="Availability" required>
					<option value="Yes">Да</option>
					<option value="No">Нет</option>
				</select>
				<?php
					require_once 'config.php';

					// Получаем список пациентов из базы данных
					$sql = "SELECT PatientID, Fullname FROM patient";
					$result = $conn->query($sql);
				?>
				<br><br>
				<label for="PatientID">ID пациента:</label>
				<select name="PatientID" id="PatientID">
					<option value="">Выбрать пациента</option>
					<?php while ($row = $result->fetch_assoc()): ?>
						<option value="<?= $row['PatientID'] ?>"><?= $row['Fullname'] ?></option>
					<?php endwhile; ?>
				</select>
				<br><br>
				<label for="DoctorID">ID врача:</label>
				<select name="DoctorID" id="DoctorID" required>
					<?php
						// Получаем список врачей из базы данных
						$sql = "SELECT DoctorID, Fullname FROM doctor";
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							echo "<option value='" . $row['DoctorID'] . "'>" . $row['Fullname'] . "</option>";
						}
					?>
				</select>
				<br><br>
				<input type="submit" name="submit" value="Создать">
			</form>
		</main>
		
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$AppointmentTime = $_POST['AppointmentTime'];
				$AppointmentDate = $_POST['AppointmentDate'];
				$Availability = $_POST['Availability'];
				$DoctorID = $_POST['DoctorID'];
				$PatientID = $_POST['PatientID'];

				// Проверяем наличие записи для выбранного врача в указанное время и дату
				$existingAppointmentSql = "SELECT * FROM appointment WHERE DoctorID = '$DoctorID' AND AppointmentDate = '$AppointmentDate' AND AppointmentTime = '$AppointmentTime'";
				$existingAppointmentResult = $conn->query($existingAppointmentSql);
				if ($existingAppointmentResult->num_rows > 0) {
					echo "Запись уже существует. Пожалуйста, выберите другое время или дату.";
					exit();
				}

				$sql = "INSERT INTO appointment (AppointmentTime, AppointmentDate, Availability, DoctorID";
				$values = "VALUES ('$AppointmentTime', '$AppointmentDate', '$Availability', '$DoctorID'";

				// Добавляем поле PatientID и его значение в запрос, если они были указаны
				if (!empty($PatientID)) {
					$Availability = 'Нет'; // Если пациент выбран, то доступность должна быть "Нет"
					$sql .= ", PatientID";
					$values .= ", '$PatientID'";
				}

				$sql .= ") " . $values . ")";

				if ($conn->query($sql) === TRUE) {
					echo "<div id='popup'>Запись успешно добавлена</div>";
					echo "<script>
							setTimeout(function() {
							var popup = document.getElementById('popup');
							popup.style.display = 'none';
							}, 5000);
						  </script>";
					exit();
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}

				$conn->close();
			}
		?>

	</div>
</body>
</html>
