<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php";

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM employees WHERE id=$id");

if($result->num_rows == 0){
 echo json_encode(["error"=>"Employee not found"]);
 exit;
}

$row = $result->fetch_assoc();

$skills = [];
$projects = [];

$s = $conn->query("SELECT skill FROM skills WHERE employee_id=$id");
while($sk = $s->fetch_assoc()){
 $skills[] = $sk['skill'];
}

$p = $conn->query("SELECT name,description FROM projects WHERE employee_id=$id");
while($pr = $p->fetch_assoc()){
 $projects[] = $pr;
}

$row['skills'] = $skills;
$row['projectsList'] = $projects;

echo json_encode($row);

?>