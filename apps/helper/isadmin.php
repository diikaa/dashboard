<?php

error_reporting(0);
session_start();

if (! isset($_SESSION['status']) || $_SESSION['status'] != 'admin') {
    header('location:../index.php');
    exit();
}
