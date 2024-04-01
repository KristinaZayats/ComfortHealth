<!DOCTYPE html>
<html>
<head>
	<title>Внесение данных о поликлинике</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="container">
<header>
		<h1>Ввод данных о полилинике</h1>
</header>
<main>
		<form action="create_hospital.php" method="post">
			<label for="name">Название:</label>
			<input type="text" name="name" id="name" required>
			<br><br><label for="address">Адрес:</label>
			<input type="text" name="address" id="address" required>
			<br><br><label for="working_hours">Рабочие часы:</label>
			<input type="text" name="working_hours" id="working_hours" required>
			<br><br><input type="submit" name="submit" value="Создать">
		</form>
	</div>
</main>
	
	<?php
	require_once 'config.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST['name'];
		$address = $_POST['address'];
		$working_hours = $_POST['working_hours'];

		$sql = "INSERT INTO hospital (Name, Address, WorkingHours) VALUES ('$name', '$address', '$working_hours')";

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
