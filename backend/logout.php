<?php
session_start();
session_destroy();
header("Location: ../FrontEndCode/Landing.html");
exit();
?>

