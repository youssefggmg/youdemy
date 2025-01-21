<?php
include "../../class/cours.php";
include "../../class/catigory.php";
include "../../instance/instace.php";
include "../../class/tag.php";
$cours = new Cours();
$cours->getConnection($pdo);
$category= new Category($pdo);
$tag = new tag($pdo);
$title = $_POST['title'];
$description = $_POST['description'];
$content_type = $_POST['content_type'];
$video_url = $_POST['video_url'];
$content = $_POST['content'];
$teacher_id = $_POST['teacher_id'];
$catigory_id = $_POST["category"];
$result=$cours->addCours($title,$description,$content,$video_url,$content_type,$teacher_id);
if ($result['status']==1) {
    $category->assignCategories($catigory_id,$result['course_id']);
    $tag->asignTags($_POST["tags"],$result['course_id']);
    
    header("location: ../../teacher/myCourses.php");
}
elseif($result['status']==0){
    header("location: ../../teacher/myCourses.php?message".urldecode($result['message']));
}


?>