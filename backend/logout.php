<?php
session_start();
session_destroy();
header("Location: ../FrontEndCode/Login.html");
exit();
?>

