<?php 
include "../../class/cours.php";
include "../../instance/instace.php";

$cours = new Cours();
$cours->getConnection($pdo);
$coursID=$_GET["courseID"];
$result = $cours->deleteCourse($coursID);
if ($result ==1) {
    header("location: ../../admine/courses.php");
}


?>