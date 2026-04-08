<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
 echo json_encode(["error"=>"No data received"]);
 exit;
}

$name = $data['name'] ?? "";
$doj = $data['doj'] ?? "";
$role = $data['role'] ?? "";
$exp = $data['exp'] ?? "";
$email = $data['email'] ?? "";
$mobile = $data['mobile'] ?? "";

$skills = $data['skills'] ?? [];
$bars = $data['bars'] ?? [];
$projects = $data['projects'] ?? [];

$sql = "INSERT INTO employees(name,doj,role,exp,email,mobile)
VALUES('$name','$doj','$role','$exp','$email','$mobile')";

if($conn->query($sql)){

 $employee_id = $conn->insert_id;

 // Save skills
 foreach($skills as $skill){
  $conn->query("INSERT INTO skills(employee_id,skill)
  VALUES($employee_id,'$skill')");
 }

 // Save projects
 foreach($projects as $project){

  $pname = $project['name'];
  $desc = $project['description'];

  $conn->query("INSERT INTO projects(employee_id,name,description)
  VALUES($employee_id,'$pname','$desc')");
 }

 echo json_encode([
   "id"=>$employee_id
 ]);

}else{

 echo json_encode([
   "error"=>$conn->error
 ]);

}