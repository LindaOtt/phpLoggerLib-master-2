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
        $this->logCollection = new \logger\LogCollection();

        $stmt = $this->database->prepare("SELECT * FROM " . self::$table);
        if($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }
        $stmt->execute();
        $stmt->bind_result($pk, $ip, $logMessageString);
        while ($stmt->fetch()) {
            $this->addLogItem($logMessageString, $includeTrace=false, $logThisObject = null, $ip);
        }
        $this->navView = new NavView();

            /*
        $this->ipAddress = $this->navView->getIp();
        assert(is_string($this->ipAddress));

        $logMessageString1 = "Message with exception object and trace";
        $includeTrace1 = true;
        $logObject1 = new \Exception("Exception 1");

        $logMessageString2 = "Message without exception object and with trace";
        $includeTrace2 = true;
        $logObject2 = null;

        $logMessageString3 = "Message with exception object and without trace";
        $includeTrace3 = false;
        $logObject3 = new \Exception("Exception 3");

        //Temporary log collection, code before data!
        $this->addLogItem($logMessageString1, $includeTrace1, $logObject1, $this->ipAddress);
        $this->addLogItem($logMessageString2, $includeTrace2, $logObject2, $this->ipAddress);
        $this->addLogItem($logMessageString3, $includeTrace3, $logObject3, $this->ipAddress);
        */
    }


    public function getLogCollection() {
        return $this->logCollection;
    }

    public function addLogItem($logMessageString, $includeTrace, $logObject, $ipAddress) {
        $this->logCollection->logWithIp($logMessageString, $includeTrace, $logObject, $ipAddress);
    }

}