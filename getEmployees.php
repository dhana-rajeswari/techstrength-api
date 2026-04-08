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

$result = $conn->query("SELECT * FROM employees");

$data = [];

while($row = $result->fetch_assoc()){

 $id = $row['id'];

 $skills = [];
 $bars = [];
 $projects = [];

 // Get skills
 $s = $conn->query("SELECT skill FROM skills WHERE employee_id=$id");
 while($sk = $s->fetch_assoc()){
  $skills[] = $sk['skill'];

  // default proficiency
  $bars[] = [$sk['skill'], 70];
 }

 // Get projects
 $p = $conn->query("SELECT name,description FROM projects WHERE employee_id=$id");
 while($pr = $p->fetch_assoc()){
  $projects[] = $pr;
 }

 // Send correct fields to React
 $row['skills'] = $skills;
 $row['bars'] = $bars;
 $row['projectsList'] = $projects;

 $data[] = $row;
}

echo json_encode($data);
?>