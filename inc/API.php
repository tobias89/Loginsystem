<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

include(ROOT.DS.'system.inc.php');


$ko = new SessionData();



if($ko->get('user')) {
	define('aviable', TRUE);
} else {
	define('aviable', FALSE);
}





?>