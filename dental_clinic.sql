-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 08, 2025 lúc 11:34 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dental_clinic`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointment`
--

CREATE TABLE `appointment` (
  `AppntID` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `ScheduleID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL,
  `Status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointment`
--

INSERT INTO `appointment` (`AppntID`, `PatientID`, `ScheduleID`, `Date`, `Start_time`, `End_time`, `Status`, `Created_At`) VALUES
(1, 1, 1, '2025-11-27', '08:00:00', '09:00:00', 'Completed', '2025-12-02 14:32:36'),
(2, 1, 1, '2025-12-03', '08:00:00', '09:00:00', 'Cancelled', '2025-12-02 14:32:36'),
(3, 2, 1, '2025-12-03', '08:00:00', '09:00:00', 'Scheduled', '2025-12-02 14:32:36'),
(4, 3, 2, '2025-12-03', '09:00:00', '10:00:00', 'Scheduled', '2025-12-02 14:32:36'),
(5, 4, 5, '2025-12-03', '14:00:00', '15:00:00', 'Scheduled', '2025-12-02 14:32:36'),
(6, 5, 2, '2025-12-03', '09:00:00', '10:00:00', 'Cancelled', '2025-12-02 14:32:36'),
(7, 1, 7, '2025-12-04', '10:00:00', '11:00:00', 'Cancelled', '2025-12-02 14:32:36'),
(8, 5, 7, '2025-12-04', '10:00:00', '11:00:00', 'Scheduled', '2025-12-02 14:32:36'),
(9, 1, 8, '2025-12-09', '08:00:00', '09:00:00', 'Scheduled', '2025-12-07 22:56:04'),
(10, 1, 12, '2025-12-10', '08:00:00', '09:00:00', 'Cancelled', '2025-12-08 22:31:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dental_service`
--

CREATE TABLE `dental_service` (
  `ServiceID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dental_service`
--

INSERT INTO `dental_service` (`ServiceID`, `Name`, `Description`) VALUES
(1, 'General Checkup', 'Routine inspection of teeth and gums'),
(2, 'Teeth Whitening', 'Laser teeth whitening procedure'),
(3, 'Root Canal', 'Treatment for infected tooth pulp'),
(4, 'Braces Consultation', 'Initial assessment for orthodontic treatment'),
(5, 'Tooth Extraction', 'Surgical removal of a tooth'),
(6, 'Dental Implant', 'Surgical component that interfaces with the bone of the jaw');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employee`
--

CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Speciality` varchar(100) DEFAULT NULL,
  `Academic_title` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employee`
--

INSERT INTO `employee` (`EmployeeID`, `UserID`, `Speciality`, `Academic_title`) VALUES
(1, 1, 'Administration', NULL),
(2, 2, 'Orthodontics', 'PhD'),
(3, 3, 'Oral Surgery', 'MSc'),
(4, 4, 'Cosmetic Dentistry', 'Specialist L1'),
(5, 5, 'Periodontics', 'PhD');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `patient`
--

CREATE TABLE `patient` (
  `PatientID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `InsuranceID` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `patient`
--

INSERT INTO `patient` (`PatientID`, `UserID`, `Email`, `Address`, `InsuranceID`) VALUES
(1, 6, 'an.nguyen@example.com', '123 Le Loi, D1', 'BHYT-001'),
(2, 7, 'be.tran@example.com', '456 Nguyen Hue, D1', 'BHYT-002'),
(3, 8, 'cuong.le@example.com', '789 Dien Bien Phu, D3', 'BHYT-003'),
(4, 9, 'dung.pham@example.com', '321 Vo Van Tan, D3', 'BHYT-004'),
(5, 10, 'em.hoang@example.com', '654 Cach Mang Thang 8, D10', 'BHYT-005');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `performs`
--

CREATE TABLE `performs` (
  `AppntID` int(11) NOT NULL,
  `ServiceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `performs`
--

INSERT INTO `performs` (`AppntID`, `ServiceID`) VALUES
(1, 1),
(2, 4),
(3, 4),
(4, 1),
(5, 5),
(6, 5),
(7, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schedule`
--

CREATE TABLE `schedule` (
  `ScheduleID` int(11) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL,
  `Capacity` int(11) DEFAULT 2,
  `Available_slot` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `schedule`
--

INSERT INTO `schedule` (`ScheduleID`, `EmployeeID`, `Date`, `Start_time`, `End_time`, `Capacity`, `Available_slot`) VALUES
(1, 2, '2025-12-03', '08:00:00', '09:00:00', 2, 1),
(2, 2, '2025-12-03', '09:00:00', '10:00:00', 2, 1),
(3, 2, '2025-12-03', '10:00:00', '11:00:00', 2, 2),
(4, 3, '2025-12-03', '08:00:00', '09:00:00', 2, 2),
(5, 3, '2025-12-03', '14:00:00', '15:00:00', 2, 1),
(6, 4, '2025-12-04', '09:00:00', '10:00:00', 2, 2),
(7, 4, '2025-12-04', '10:00:00', '11:00:00', 2, 1),
(8, 2, '2025-12-09', '08:00:00', '09:00:00', 2, 1),
(9, 2, '2025-12-10', '08:00:00', '09:00:00', 2, 2),
(10, 2, '2025-12-10', '09:00:00', '10:00:00', 2, 2),
(11, 2, '2025-12-10', '10:00:00', '11:00:00', 2, 2),
(12, 3, '2025-12-10', '08:00:00', '09:00:00', 2, 2),
(13, 3, '2025-12-10', '14:00:00', '15:00:00', 2, 2),
(14, 4, '2025-12-11', '09:00:00', '10:00:00', 2, 2),
(15, 4, '2025-12-11', '10:00:00', '11:00:00', 2, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password_hash` varchar(255) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `NationalID` varchar(20) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Role` enum('Patient','Receptionist','Doctor') NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password_hash`, `Name`, `NationalID`, `Phone`, `Age`, `Role`, `Created_At`) VALUES
(1, 'admin_sarah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah Receptionist', '001098000001', '0901000001', 28, 'Receptionist', '2025-12-02 14:32:36'),
(2, 'dr_nghia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Trang Thanh Nghia', '001088000001', '0902000001', 45, 'Doctor', '2025-12-02 14:32:36'),
(3, 'dr_dung', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Nguyen Duc Dung', '001088000002', '0902000002', 50, 'Doctor', '2025-12-02 14:32:36'),
(4, 'dr_vananh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Nguyen Thi Van Anh', '001088000003', '0902000003', 38, 'Doctor', '2025-12-02 14:32:36'),
(5, 'dr_minh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Tran Tien Minh', '001088000004', '0902000004', 42, 'Doctor', '2025-12-02 14:32:36'),
(6, 'pat_an', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyen Van An', '079090000001', '0912345678', 30, 'Patient', '2025-12-02 14:32:36'),
(7, 'pat_be', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tran Thi Be', '079090000002', '0987654321', 25, 'Patient', '2025-12-02 14:32:36'),
(8, 'pat_cuong', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Le Van Cuong', '079090000003', '0909090909', 32, 'Patient', '2025-12-02 14:32:36'),
(9, 'pat_dung', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pham Thi Dung', '079090000004', '0912223333', 29, 'Patient', '2025-12-02 14:32:36'),
(10, 'pat_em', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hoang Van Em', '079090000005', '0933444555', 22, 'Patient', '2025-12-02 14:32:36');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AppntID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `ScheduleID` (`ScheduleID`);

--
-- Chỉ mục cho bảng `dental_service`
--
ALTER TABLE `dental_service`
  ADD PRIMARY KEY (`ServiceID`);

--
-- Chỉ mục cho bảng `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PatientID`),
  ADD UNIQUE KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `performs`
--
ALTER TABLE `performs`
  ADD PRIMARY KEY (`AppntID`,`ServiceID`),
  ADD KEY `ServiceID` (`ServiceID`);

--
-- Chỉ mục cho bảng `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`ScheduleID`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `NationalID` (`NationalID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `appointment`
--
ALTER TABLE `appointment`
  MODIFY `AppntID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `dental_service`
--
ALTER TABLE `dental_service`
  MODIFY `ServiceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `employee`
--
ALTER TABLE `employee`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `patient`
--
ALTER TABLE `patient`
  MODIFY `PatientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `schedule`
--
ALTER TABLE `schedule`
  MODIFY `ScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`PatientID`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`ScheduleID`) REFERENCES `schedule` (`ScheduleID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `performs`
--
ALTER TABLE `performs`
  ADD CONSTRAINT `performs_ibfk_1` FOREIGN KEY (`AppntID`) REFERENCES `appointment` (`AppntID`) ON DELETE CASCADE,
  ADD CONSTRAINT `performs_ibfk_2` FOREIGN KEY (`ServiceID`) REFERENCES `dental_service` (`ServiceID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employee` (`EmployeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
