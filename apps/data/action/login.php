<?php

if (! isset($_POST['e_user']) || ! isset($_POST['e_pass']) ) {
	jumpToIndex();
	exit();
}

require_once('../model/class.db.php');

$login['user'] = $_POST['e_user'];
$login['pass'] = md5($_POST['e_pass']);

$db = new DB();
$db->set_default_connection();
$query = getUserQuery($login);

if (isLogin($query)) {
	
	$res = $db->select($query);
	$data = mysql_fetch_array($res);

	session_start();
	$_SESSION['nik'] = $login['user'];
	$_SESSION['prev'] = $data['prev_user'];
	$_SESSION['login'] = 'loginOK';
	$_SESSION['status'] = $_SESSION['prev'] == 1 ? 'admin' : 'user';

	jumpToAppPage($_SESSION['status']);

} else {

	jumpToIndex();

}

unset($db);


function getUserQuery($login)
{
	global $db;

	$user = $login['user'];
	$pass = $login['pass'];

	$query =  "SELECT * FROM user WHERE nik='$user' AND pass_user='$pass'";

	return $query;
}	


function isLogin($query)
{
	global $db;

	return $db->get_query_rows($query) == 0 ? FALSE : TRUE;
}


function jumpToAppPage($status)
{
	if ($status == 'admin') {
		header('location:../admin.php');
		exit();
	}
	
	header('location:../user.php');	
}


function jumpToIndex()
{
	header('location:../index.php?error=invalid');
}