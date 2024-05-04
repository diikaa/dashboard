<?php

function getProtectedRole()
{
	// list of protected role
	// sistem can't delete this role
	// [nama_previlage] => [id_previlage]
	$protected_role = [
		'administrator' => 1,
		'default' => 2
	];

	return $protected_role;
}