<?php

namespace logger;

require_once("LogItem.php");

class LogCollection {
	private $logArray = array();
	

	/**
	* Logging Method
	* @param string $logMessageString The message you intend to log
	* @param mixed $logThisObject An object which state you want to log 
	* @param boolean $includeTrace save callstack
	* @param string $class a userdefined class can be bound to css
	* @return void
	*/
	public function log($string, $trace = false, $object = null, $class = "normal") {
		$this->logArray[] = new LogItem($string, $trace, $object);
	}
	
	/**
	* @return array of logger/LogItem
	*/
	public function getList() {
		return $this->logArray;
	}
}
