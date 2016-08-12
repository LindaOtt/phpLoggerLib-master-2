<?php

namespace logger;

require_once("./view/NavView.php");
require_once("./model/LogCollection.php");
require_once("LogItemWithIP.php");

class LogDAL {

    private $logCollection;
    private $navView;
    //private $ipAddress;
    private static $table = "Logs";

    public function __construct(\mysqli $db) {

        $this->database = $db;
        $this->logCollection = new LogCollection();
    }


    public function getLogCollection() {

        $preparestmt = "SELECT * FROM " . self::$table;
        $preparestmt .= " ORDER BY datetime ASC";

        $stmt = $this->database->prepare($preparestmt);
        if($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }
        $stmt->execute();
        $stmt->bind_result($pk, $ip, $logMessageString, $sessionid, $datetime);
        while ($stmt->fetch()) {
            $this->addLogItem($logMessageString, $includeTrace=false, $logThisObject = null, $ip, $sessionid, $datetime);
        }
        $this->navView = new NavView();

        return $this->logCollection;
    }


    public function addLogItem($logMessageString, $includeTrace, $logObject, $ipAddress, $sessionid, $datetime) {
        $this->logCollection->logWithIp($logMessageString, $includeTrace, $logObject, $ipAddress, $sessionid, $datetime);
    }

}