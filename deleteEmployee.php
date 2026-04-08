<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "db.php";

$id=$_GET['id'];

$conn->query("DELETE FROM employees WHERE id=$id");

echo json_encode(["message"=>"Deleted"]);

?>