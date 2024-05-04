<?php

session_start();
unset($_SESSION['nik']);
unset($_SESSION['prev']);
unset($_SESSION['login']);
unset($_SESSION['status']);
session_destroy();

header('location:../index.php');

?>