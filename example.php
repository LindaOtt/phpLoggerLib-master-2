<?php

require_once("Logger.php");


function loggStuff() {
	loggHeader("A header");
	loggThis("write a message");
	loggThis("include call trace", null, true);
	loggThis("include an object", new \Exception("foo exception"), false);
}

loggStuff();

//show log
//do not dump superglobals
echoLog(false);

//show with superglobals
echoLog();




