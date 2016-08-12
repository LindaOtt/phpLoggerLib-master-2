<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("Logger.php");
require_once("controller\LogManager.php");

function loggStuff() {
	loggHeader("A header");
	loggThis("write a message");
	loggThis("include call trace", null, true);
	loggThis("include an object", new \Exception("foo exception"), false);
}



//Try out the log manager
$logManager = new \logger\LogManager();
$logManager->handleInput();

//loggStuff();

//show log
//do not dump superglobals
//echoLog(false);

//show with superglobals
//echoLog();




