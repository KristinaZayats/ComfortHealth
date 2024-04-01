<!DOCTYPE html>
<html>
<head>
	<title>Занесение информации о враче</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
<header>
		<h1>Ввод информации  о враче</h1>
</header>
<main>
		<form action="create_doctor.php" method="post">
			<label for="fullname">ФИО:</label>
			<input type="text" name="fullname" id="fullname" required>
			<br><br><label for="Speciality">Специализация:</label>
			<input type="text" name="Speciality" id="Speciality" required>
			<br><br><label for="RoomNumber">Номер кабинета:</label>
			<input type="text" name="RoomNumber" id="RoomNumber" required>
			<br><br><label for="PhoneNumber">Номер телефона:</label>
			<input type="text" name="PhoneNumber" id="PhoneNumber" required>
			<br><br><label for="Email">Email:</label>
			<input type="text" name="Email" id="Email" required>
			<br><br><label for="HospitalID">ID поликлиники:</label>
			<input type="text" name="HospitalID" id="HospitalID" required>
			<br><br><input type="submit" name="submit" value="Создать">
		</form>
	</div>
</main>
	
	<?php
	require_once 'config.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$fullname = $_POST['fullname'];
		$Speciality = $_POST['Speciality'];
		$RoomNumber = $_POST['RoomNumber'];
		$PhoneNumber = $_POST['PhoneNumber'];
		$Email = $_POST['Email'];
		$HospitalID = $_POST['HospitalID'];

		$sql = "INSERT INTO doctor (Fullname, Speciality, RoomNumber, PhoneNumber, Email, HospitalID) VALUES ('$fullname', '$Speciality', '$RoomNumber', '$PhoneNumber', '$Email', '$HospitalID')";

		if ($conn->query($sql) === TRUE) {
			echo "<div id='popup'>Новая запись успешно добавлена</div>";
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

</body>
</html>
