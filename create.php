<!DOCTYPE html>
<html>
<head>
	<title>Создание</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<h1>Выбор таблицы для создания новой записи</h1>
	</header>
<main>
	<form method="post" action="">
<label for="table">Выберите таблицу для ввода новой информации:</label>
		<select name="table_name">
			<option value="Patient">Пациент</option>
			<option value="Appointment">Запись</option>
			<option value="Doctor">Врач</option>
			<option value="Hospital">Поликлиника</option>
		</select>
		<br><br>
		<input type="submit" name="submit" value="Подтвердить">
	</form>
</main>
	<?php
	// Если кнопка "Подтвердить" нажата
	if(isset($_POST['submit'])){
		// Получаем выбранное пользователем название таблицы
		$table_name = $_POST['table_name'];

		// Проверяем, что пользователь выбрал таблицу
		if(empty($table_name)){
			echo "<p style='color:red;'>Выберите таблицу</p>";
		}
		else{
			// Перенаправляем на страницу создания новой записи для выбранной таблицы
			switch($table_name){
				case 'Patient':
					header('Location: create_patient.php');
					break;
				case 'Appointment':
					header('Location: create_appointment.php');
					break;
				case 'Doctor':
					header('Location: create_doctor.php');
					break;
				case 'Hospital':
					header('Location: create_hospital.php');
					break;
				default:
					echo "<p style='color:red;'>Выбрана неверная таблица</p>";
					break;
			}
		}
	}
	?>

</body>
</html>
