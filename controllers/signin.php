<?php
include "../class/user.php";
include "../instance/instace.php";
$user = new User($pdo);
$result = $user->logIn($_POST["email"], $_POST["password"]);
$resultData = $result["message"];
if ($result["status"] == 1) {
    setcookie("userID", $resultData["id"], time() + 86400, "/");
    setcookie("userROLE", $resultData["user_type"], time() + 86400, "/");
    if ($resultData["user_type"] == "Student") {
        header("Location: ../user/index.php");
    } else {
        header("location: ");
    }
} else {
    header("location: ../index.php?error=" . urlencode($resultData));
}


?>