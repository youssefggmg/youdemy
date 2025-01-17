<?php
setcookie("userROLE", "", time() - 3600,"/");
setcookie("userID", "", time() - 3600,"/");
header("location: ../index.php")
?>