<?php

namespace model;

class NoMessageException extends \Exception {};
class NoIpException extends \Exception {};
class NoSessionIdException extends \Exception{};
class NoDateTimeException extends \Exception{};

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
     * @var number of distinct sessions for this ip
     */
    public $m_numberofsessions;


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
     * @var date and time, stored in datetime format in database
     */
    public $m_dateTime;


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
	public function __construct($logMessageString, $includeTrace = null, $logThisObject = null, $ip, $sessionid, $datetime) {

        if (is_string($logMessageString) == false || strlen($logMessageString) == 0)
            //throw new NoMessageException();
            $logThisObject = new NoMessageException();
        if (is_string(empty($ip)) || strlen($ip) == 0)
            //throw new NoIpException();
            $logThisObject = new NoIpException();
        if (is_string($sessionid) == false || strlen($sessionid) == 0)
            //throw new NoSessionIdException();
            $logThisObject = new NoSessionIdException();
        if (is_string($datetime) == false || strlen($datetime) == 0)
            //throw new NoDateTimeException();
            $logThisObject = new NoDateTimeException();


	    $this->m_dateTime = $datetime;

		$this->m_message = $logMessageString;

        $this->m_ip = $ip;

        $this->m_sessionid = $sessionid;

        $this->m_debug_backtrace = null;

		if ($logThisObject != null) {
            $this->m_object = var_export($logThisObject, true);
            $this->m_debug_backtrace = debug_backtrace();
        }

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