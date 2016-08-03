PHP Logger Library
=============

A simple logging library collecting and giving a little more information 

	//Example code
	require_once("Logger.php");


	loggHeader("write a message");
	loggThis("write a message");
	loggThis("include call trace", true);
	loggThis("include an object", false, new \Exception("foo exception"));

	echoLog();