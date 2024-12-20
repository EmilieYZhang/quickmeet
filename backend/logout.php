<?php
session_start();
session_destroy();
header("Location: ../backend/Login.php");
exit();
?>

