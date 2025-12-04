<?php
// require_once "./Models/Appointment.php";
require_once __DIR__ . '/../Models/Appointment.php';

$input = json_decode(file_get_contents("php://input"), true);
$task = $input["task"] ?? null;
header("Content-Type: application/json");
if (!$task) {
    $task = $_POST["task"] ?? null;
}
switch ($task) {
    case 'getSlots':
        $date = $input["date"] ?? null;
        if (!$date) {
            echo json_encode(["slots" => []]);
            exit;
        }
        $slots = AppointmentModel::getAvailableSlots($date);
        echo json_encode(["slots" => $slots]);
        break;
    case 'submitslot':
        $slot = $input["slot"] ?? null;
        $date = $input["date"] ?? null;
        if (!$slot || !$date) {
            echo json_encode(["error" => "Invalid slot or date"]);
            exit;
        }
        
        echo json_encode(AppointmentModel::CreateAppointment(1,$slot["id"],$date,$slot["time"])); // Giả sử PatientID = 1
        break;
    case 'getAppointments':
        echo json_encode(AppointmentModel::getCurrentAppontment(1));// Giả sử PatientID = 1
        break;
    case 'CancelAppointment':
        $id = $input['Id'] ?? null;
        $schedule_id = $input['ScheduleID'] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "error" => "No ID provided"]);
            exit;
        }
        if (!$schedule_id) {
            echo json_encode(["success" => false, "error" => "No Schedule ID provided"]);
            exit;
        }
        echo json_encode(AppointmentModel::CancelAppointment($id,$schedule_id));
        break;
    default:
        echo json_encode(["error" => "Invalid task"]);
        exit;
}
?>