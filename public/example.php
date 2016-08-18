<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("Logger.php");
$root =  dirname(dirname(__FILE__));
require_once($root.'/application/controller/LogManager.php');
require_once($root.'/application/view/HTMLView.php');

function loggStuff() {
	loggHeader("A header");
	loggThis("write a message");
	loggThis("include call trace", null, true);
	loggThis("include an object", new \Exception("foo exception"), false);
}

//Try out the log manager
$logManager = new \controller\LogManager();
$logManager->handleInput();

$view = array();
$view = $logManager->generateOutput();
$htmlView = new \view\HTMLView("utf-8");
echo $htmlView->getHTMLPage("Assignment 4 Log Manager", $view);

//loggStuff();

//show log
//do not dump superglobals
//echoLog(false);

//show with superglobals
//echoLog();