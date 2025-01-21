<?php 
include "../../class/cours.php";
include "../../instance/instace.php";
$cours = new Cours();
$cours->getConnection($pdo);
$category= new Category($pdo);
$title = $_POST['title'];
$description = $_POST['description'];
$content_type = $_POST['content_type'];
$video_url = $_POST['video_url'];
$content = $_POST['content'];
$Cours_ID = $_POST['course_id'];
$catigory_id = $_POST["category"];
$cours->updateCourse($Cours_ID,$title,$description,$content ,$video_url,$content_type);

?>