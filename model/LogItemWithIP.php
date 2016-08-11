<?php

//This has no namespace for convenience, it should really be a common module
namespace logger;

class LogItemWithIP {
	//Maybe add some information hiding
	/**
	* @var String
	*/
	public $m_message;
	
	/**
	* @var mixed or null
	*/
	public $m_object;


    /**
     * @var ip address
     */
    public $m_ip;


    /**
	* @var array From debug_backtrace or null
	*/
	public $m_debug_backtrace;


	/**
	* @var String script location
	*/
	public $m_calledFrom;

	/**
	* @var Unix microtime 
	* see http://se1.php.net/manual/en/function.microtime.php
	*/
	public $m_microTime;


    /**
     * @var Id of current session
     */
    public $m_sessionid;
	
	
	/**
	* Create a log item
	*
	* @param string $logMessageString The message you intend to log
	* @param mixed $logThisObject An object which state you want to log 
	* @param boolean $includeTrace save callstack
	* @return void
	*/
	public function __construct($logMessageString, $includeTrace = false, $logThisObject = null, $ip, $sessionid, $microtime) {

	    $this->m_microTime = $microtime;

		$this->m_message = $logMessageString;

        $this->m_ip = $ip;

        $this->m_sessionid = $sessionid;

		if ($logThisObject != null)
			$this->m_object = var_export($logThisObject, true);
		
		$this->m_debug_backtrace = debug_backtrace();

		$this->m_calledFrom = $this->cleanFilePath($this->m_debug_backtrace[2]["file"]) . " " . $this->m_debug_backtrace[2]["line"];


		if (!$includeTrace) {
			$this->m_debug_backtrace = null;
		}
		
	}
	
	/**
	* removes full path
	* @param $path String the url of a script
	* @return string a path
	*/
	public static function cleanFilePath($path) {
		if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]))
			return substr($path, strlen($_SERVER["CONTEXT_DOCUMENT_ROOT"]));
		
		$fullLength = strlen($_SERVER["SCRIPT_FILENAME"]); //P:/php/2013 secret/phpLoggerLib/example.php
		$partLength = strlen($_SERVER["PHP_SELF"]); // /2013secret/phpLoggerLib/example.php

		return substr($path, $fullLength - $partLength);
	}
	 
}