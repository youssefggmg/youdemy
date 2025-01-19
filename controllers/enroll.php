<?php 
include "../class/student.php";
include "../instance/instace.php";
$student = new Student($pdo);
$result=$student->enrollInCourse($_GET["cousID"],$_GET["userID"]);
if ($result["status"==1]) {
    header("location: ../user/myCourses.php");
}
?>