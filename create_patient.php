<!DOCTYPE html>
<html>
<head>
	<title>Внесение информации о пациенте</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
<header>
		<h1>Ввод информации о пациенте</h1>
</header>
<main>
		<form action="create_patient.php" method="post">
			<label for="fullname">ФИО:</label>
			<input type="text" name="fullname" id="fullname" required>
			<br><br><label for="birthdate">Дата рождения:</label>
			<input type="date" name="birthdate" id="birthdate" required>
			<br><br><label for="address">Адрес:</label>
			<input type="text" name="address" id="address" required>
			<br><br><label for="phonenumber">Номер телефона:</label>
			<input type="tel" name="phonenumber" id="phonenumber" required>
			<br><br><label for="InsuranceNumber">Номер полиса:</label>
			<input type="text" name="InsuranceNumber" id="InsuranceNumber" required>
			<br><br><label for="Email">Email:</label>
			<input type="email" name="Email" id="Email" required>
			<br><br><label for="Sex">Пол:</label>
			<select name="Sex" id="Sex">
				<option value="Male">Мужской</option>
				<option value="Female">Женский</option>
			</select>
			<br><br><input type="submit" name="submit" value="Создать">
		</form>
	</div>
</main>
	
	<?php
	require_once 'config.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$fullname = $_POST['fullname'];
		$birthdate = $_POST['birthdate'];
		$address = $_POST['address'];
		$phonenumber = $_POST['phonenumber'];
		$InsuranceNumber = $_POST['InsuranceNumber'];
		$Email = $_POST['Email'];
		$Sex = $_POST['Sex'];

		$sql = "INSERT INTO patient (Fullname, Birthdate, Address, PhoneNumber, InsuranceNumber, Email, Sex) VALUES ('$fullname', '$birthdate', '$address', '$phonenumber', '$InsuranceNumber', '$Email', '$Sex')";

		if ($conn->query($sql) === TRUE) {
			echo "<div id='popup'>Новая запись добавлена успешно</div>";
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
