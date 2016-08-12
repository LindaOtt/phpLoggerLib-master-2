<?php

namespace logger;

require_once("LogItem.php");
require_once("LogItemWithIP.php");

class LogCollection {
	private $logArray = array();
	private $sortedIpArray = array();
    private $sortedIpArrayCounter = array();

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

    public function logWithIP($string, $trace = false, $object = null, $ip, $sessionid, $datetime) {
        $this->logArray[] = new LogItemWithIP($string, $trace, $object, $ip, $sessionid, $datetime);
    }
	
	/**
	* @return array of logger/LogItem
	*/
	public function getList() {
		return $this->logArray;
	}

	public function getSortedIpList() {
	    if (empty($this->logArray)) {
	        throw new Exception('There are no recorded log items.');
        }
        else {
            $counter = 0;
            foreach ($this->logArray as $item) {
                $ip = $item->m_ip;
                $sessionid = $item->m_sessionid;
                $datetime = $item->m_dateTime;

                $this->sortedIpArray[] = array(
                    $ip,
                    $sessionid,
                    $datetime)
                ;
                $counter++;
            }
        }
        //return $this->sortedIpArray;
        $counter = 0;
        foreach ($this->sortedIpArray as $item) {
            $ip = $item[0];
            if (array_key_exists($ip, $this->sortedIpArrayCounter)) {
                $countervalue = $this->sortedIpArrayCounter[$ip]+1;
                $this->sortedIpArrayCounter[$ip] = $countervalue;
            }
            else {
                $this->sortedIpArrayCounter[$ip] = 1;
            }

            $counter++;
        }
        return $this->sortedIpArrayCounter;
    }
}
