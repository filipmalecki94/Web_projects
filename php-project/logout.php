<?php

session_start();

header('Location: login.php');

session_unset();	
$conn->close();

?>