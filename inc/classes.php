<?php
class Object {

	var $data;

	function Object() {

		$this->initzialize();
	}

	function initzialize() {
		// Wird beim Erstellen der Klassen aufgerufen
	}

	function get($aValue) {

		if(array_key_exists($aValue, $this->data)) {
			return $this->data[$aValue];
		} else {
			return NULL;
		}
	}

	function set($aKey, $aValue) {

		$this->data[$aKey] = $aValue;
	}

	function childMethodeNeeded($aMethode) {

		$className = $this->className();
		echo "Die Klasse $className braucht die Methode: $aMethode!<br />";
	}


}

class User extends Object {

	function initzialize() {

		$this->data['id'] = $this->getID();
	}

	function getID() {

		return uniqid();
	}
}


class Authorization extends Object {

	var $users;

	function initzialize() {
		
		$sessionData = new SessionData();

		$this->users = $sessionData->get('users');

		$this->initzializeDataArray();
	}

	function initzializeDataArray() {

		$users = $this->users;

		if(is_null($users)) {
			return NULL;
		} else {

			foreach($users as $key => $user) {

				$this->data[$user->get('login')] = $user;
			}
		}
	}

}

class SessionData extends Object {

	function initzialize() {

		$this->data = $_SESSION;
	}
}

class SessionSaveHandler {
	protected $savePath;
	protected $sessionName;

	public function __construct() {
		session_set_save_handler(
		array($this, "oeffne"),
		array($this, "schliesse"),
		array($this, "lese"),
		array($this, "schreibe"),
		array($this, "loesche"),
		array($this, "gc")
		);
	}

	public function oeffne($speicherpfad, $session_name) {

		global $sess_speicherpfad;

		$sess_speicherpfad = $speicherpfad;
		return(true);
	}

	public function schliesse()	{

		return(true);
	}

	public function lese($id) {

		global $sess_speicherpfad;

		$sess_datei = "$sess_speicherpfad/sess_$id";
		return (string) @file_get_contents($sess_datei);
	}

	public function schreibe($id, $sess_daten) {

		global $sess_speicherpfad;

		$sess_datei = "$sess_speicherpfad/sess_$id";
		if ($fp = @fopen($sess_datei, "w")) {
			$return = fwrite($fp, $sess_daten);
			fclose($fp);
			return $return;
		} else {
			return(false);
		}
	}

	public function loesche($id) {
		global $sess_speicherpfad;

		$sess_datei = "$sess_speicherpfad/sess_$id";
		return(@unlink($sess_datei));
	}

	public function gc($maxlifetime) {
		global $sess_speicherpfad;

		foreach (glob("$sess_speicherpfad/sess_*") as $dateiname) {
			if (filemtime($dateiname) + $maxlifetime < time()) {
				@unlink($dateiname);
			}
		}
		return true;
	}
}
?>