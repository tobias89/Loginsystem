<?php
function loadUserData() {

	$users = array();
	$authorizations = array();
	include('users.php');

	foreach ($users as $key => $value) {

		// Neuen User erstellen und Username setzen
		$benutzer = new User();
		$benutzer->set('username', $key);

		// Nun alle Benutzerparameter setzen
		foreach($value as $name => $parameter) {
			$benutzer->set($name, $parameter);
		}

		$authorizations[$benutzer->get('login')] = $benutzer;
	}

	return $authorizations;
}
function login() {

	$login = $_POST['login'];
	$password = $_POST['password'];

	$userCheck = checkUser($login, $password);

	if($userCheck) {
		userLogin($userCheck);
	} else {
		userOnError();
	}
}
function userLogin($aUser) {
	$_SESSION['user'] = $aUser;

	echo "Sie wurden erfolgreich eingeloggt!";
}
function userOnError() {
	echo "Falscher Benutzername oder Passwort";
}
function handleRequestAction($aFunction = FALSE) {

	if($aFunction) {
		call_user_func($aFunction);
	}
}
function load($aFile) {
	include(ROOT.DS.$aFile.'.php');
}
function checkUser($login, $password) {

	$error = FALSE;

	$obj = new Authorization;
	$user = $obj->get($login);

	if(is_null($user)) {
		$error = TRUE;
	} else {
		if($user->get('password') != $password) {
			$error = TRUE;
		}
	}

	if($error) {
		return FALSE;
	} else {
		return $user;
	}

}
function loginForm() {
	echo '<form method="post">
	Benutzername: &nbsp;<input type="text" name="login"><br /> Passwort:
	&nbsp;<input type="text" name="password"><br /> <input type="hidden"
	name="submit"><input type="hidden" name="handleRequestAction" value="login"> <input type="submit">
	</form>';	
}

load('classes');

$handler = new SessionSaveHandler();
register_shutdown_function('session_write_close');
session_start();

$_SESSION['users'] = loadUserData();


if(isset($_POST['handleRequestAction'])) {
	call_user_func('handleRequestAction', $_POST['handleRequestAction']);
}


?>