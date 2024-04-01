-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 24 2023 г., 17:15
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `polyclinic`
--

-- --------------------------------------------------------

--
-- Структура таблицы `appointment`
--

CREATE TABLE `appointment` (
  `AppointmentID` int(11) NOT NULL,
  `AppointmentTime` time NOT NULL,
  `AppointmentDate` date NOT NULL,
  `Availability` enum('Yes','No') NOT NULL,
  `DoctorID` int(11) DEFAULT NULL,
  `PatientID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `appointment`
--

INSERT INTO `appointment` (`AppointmentID`, `AppointmentTime`, `AppointmentDate`, `Availability`, `DoctorID`, `PatientID`) VALUES
(1, '12:00:00', '2023-04-29', 'Yes', 1, NULL),
(2, '13:00:00', '2023-04-29', 'Yes', 1, NULL),
(3, '14:00:00', '2023-04-29', 'Yes', 1, NULL),
(4, '09:08:00', '2023-04-29', 'Yes', 2, NULL),
(10, '17:02:00', '2023-05-30', 'Yes', 2, NULL),
(11, '20:43:00', '2023-05-31', 'No', 1, 2),
(12, '20:43:00', '2023-05-31', 'Yes', 5, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `doctor`
--

CREATE TABLE `doctor` (
  `DoctorID` int(11) NOT NULL,
  `Fullname` text NOT NULL,
  `Speciality` text NOT NULL,
  `RoomNumber` int(11) NOT NULL,
  `PhoneNumber` text NOT NULL,
  `Email` text NOT NULL,
  `HospitalID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `doctor`
--

INSERT INTO `doctor` (`DoctorID`, `Fullname`, `Speciality`, `RoomNumber`, `PhoneNumber`, `Email`, `HospitalID`) VALUES
(1, 'Doctor 1', 'Speciality1', 46, '89561548656', 'a1@mail.ru', 1),
(2, 'Doctor 2', 'Speciality2', 102, '89561548725', 'a2@mail.ru', 1),
(3, 'Doctor 3', 'Speciality3', 98, '89561544364', 'a3@mail.ru', 2),
(4, 'Doctor 4', 'Speciality4', 51, '89567542368', 'a4@mail.ru', 2),
(5, 'Doctor 5', 'Speciality5', 35, '89567542375', 'a5@mail.ru', 3),
(6, 'Doctor 6', 'Speciality6', 76, '89567487525', 'a6@mail.ru', 3),
(9, 'Doctor 7', 'Speciality7', 73, '85422695351', 'sa@a.ru', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `hospital`
--

CREATE TABLE `hospital` (
  `HospitalID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Address` text NOT NULL,
  `WorkingHours` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `hospital`
--

INSERT INTO `hospital` (`HospitalID`, `Name`, `Address`, `WorkingHours`) VALUES
(1, 'Hospital1', 'Address63', '8:00-22:00'),
(2, 'Hospital2', 'Address64', '8:00-21:00'),
(3, 'Hospital3', 'Address65', '8:00-21:00');

-- --------------------------------------------------------

--
-- Структура таблицы `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `Fullname` text NOT NULL,
  `Birthdate` date NOT NULL,
  `Address` text NOT NULL,
  `PhoneNumber` text NOT NULL,
  `InsuranceNumber` text NOT NULL,
  `Email` text NOT NULL,
  `Sex` enum('F','M') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `patient`
--

INSERT INTO `patient` (`PatientID`, `Fullname`, `Birthdate`, `Address`, `PhoneNumber`, `InsuranceNumber`, `Email`, `Sex`) VALUES
(1, 'Patient1', '1995-02-15', 'Addr40', '89214156759', '1111111111111111', 'b1@mail.ru', 'F'),
(2, 'Patient2', '1998-03-25', 'Addr27', '89214156700', '2222222222222222', 'b2@mail.ru', 'F'),
(3, 'Patient3', '1988-02-01', 'Addr103', '89214157845', '3333333333333333', 'b3@mail.ru', 'M'),
(4, 'Patient4', '1972-12-17', 'Addr97', '89217541258', '4444444444444444', 'b4@mail.ru', 'M');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AppointmentID`),
  ADD KEY `ID пациента` (`PatientID`),
  ADD KEY `ID врача` (`DoctorID`);

--
-- Индексы таблицы `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`DoctorID`),
  ADD KEY `ID поликлиники` (`HospitalID`);

--
-- Индексы таблицы `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`HospitalID`);

--
-- Индексы таблицы `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `appointment`
--
ALTER TABLE `appointment`
  MODIFY `AppointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `doctor`
--
ALTER TABLE `doctor`
  MODIFY `DoctorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `hospital`
--
ALTER TABLE `hospital`
  MODIFY `HospitalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`DoctorID`);

--
-- Ограничения внешнего ключа таблицы `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`HospitalID`) REFERENCES `hospital` (`HospitalID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
