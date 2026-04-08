<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "db.php";

/* Read JSON body */
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

/* Validate request */
if (!$data) {
    echo json_encode([
        "error" => "No data received",
        "raw" => $raw
    ]);
    exit;
}

/* Safe values */
$id = $data['id'] ?? null;
$name = $data['name'] ?? "";
$doj = $data['doj'] ?? "";
$role = $data['role'] ?? "";
$exp = $data['exp'] ?? "";
$email = $data['email'] ?? "";
$mobile = $data['mobile'] ?? "";

$skills = $data['skills'] ?? [];
$projects = $data['projects'] ?? [];

/* Validate id */
if (!$id) {
    echo json_encode(["error" => "Employee ID missing"]);
    exit;
}

/* Update employee */
$sql = "UPDATE employees SET 
name='$name',
doj='$doj',
role='$role',
exp='$exp',
email='$email',
mobile='$mobile'
WHERE id=$id";

if ($conn->query($sql)) {

    /* Remove old skills */
    $conn->query("DELETE FROM skills WHERE employee_id=$id");

    /* Insert new skills */
    foreach ($skills as $skill) {
        $skill = $conn->real_escape_string($skill);
        $conn->query("INSERT INTO skills(employee_id,skill)
        VALUES($id,'$skill')");
    }

    /* Remove old projects */
    $conn->query("DELETE FROM projects WHERE employee_id=$id");

    /* Insert new projects */
    foreach ($projects as $project) {

        $pname = $conn->real_escape_string($project['name'] ?? "");
        $desc  = $conn->real_escape_string($project['description'] ?? "");

        $conn->query("INSERT INTO projects(employee_id,name,description)
        VALUES($id,'$pname','$desc')");
    }

    echo json_encode([
        "success" => true,
        "id" => $id,
        "message" => "Employee updated successfully"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "error" => $conn->error
    ]);

}

?>