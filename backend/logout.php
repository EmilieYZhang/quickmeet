<?php
session_start();
session_destroy();
header("Location: ../backend/Landing.html");
exit();
?>

