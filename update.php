<?php
    // подключаем файл конфигурации
    require_once "config.php";

    // массив названий таблиц для обновления
    $tables = array("Patient", "Doctor", "Appointment", "Hospital");

    // проверяем, была ли выбрана таблица
    if(isset($_POST['table'])){
        $table = $_POST['table'];

        // проверяем, выбрана ли действительно существующая таблица
        if(!in_array($table, $tables)){
            die("Выбрана неверная таблица");
        }

        // перенаправляем на страницу обновления для выбранной таблицы
        header("Location: update_" . strtolower($table) . ".php");
        exit;
    }

    // выводим форму выбора таблицы для обновления
?>
<!DOCTYPE html>
<html>
<head>
   	<title>Изменение данных</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<h1>Изменение данных</h1>
</header>
<main>
<form method="post" action="">
<label for="table">Выберите таблицу для редактирования данных:</label>
		<select name="table_name">
			<option value="Patient">Пациент</option>
			<option value="Appointment">Запись</option>
			<option value="Doctor">Врач</option>
			<option value="Hospital">Поликлиника</option>
		</select>
<br><br>
		<input type="submit" name="submit" value="Подтвердить">
</main>
	</form>
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
					header('Location: update_patient.php');
					break;
				case 'Appointment':
					header('Location: update_appointment.php');
					break;
				case 'Doctor':
					header('Location: update_doctor.php');
					break;
				case 'Hospital':
					header('Location: update_hospital.php');
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
