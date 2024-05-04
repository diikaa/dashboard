<?php

error_reporting(0);
session_start();

if (! isset($_SESSION['login']) || $_SESSION['login'] != 'loginOK') {
    header('location:../index.php');
    exit();
}
