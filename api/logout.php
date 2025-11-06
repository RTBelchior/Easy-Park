<?php
session_start();
session_unset();
session_destroy();
header('Location: /Easy-Park/index.php');
exit();
?>
