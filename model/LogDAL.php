<?php

namespace logger;

require_once("./view/NavView.php");
require_once("./model/LogCollection.php");
require_once("LogItemWithIP.php");

class LogDAL {

    private $logCollection;
    private $navView;
    private static $db = "logs";
    private static $table = "logs";
    private $message;

    public function __construct(\mysqli $db) {

        $this->database = $db;
        $this->logCollection = new LogCollection();
    }


    public function getLogCollection() {

        $preparestmt = "SELECT * FROM " . self::$table;
        $preparestmt .= " ORDER BY datetime DESC";

        $stmt = $this->database->prepare($preparestmt);
        if($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }
        $stmt->execute();
        $stmt->bind_result($pk, $ip, $logMessageString, $sessionid, $datetime);
        while ($stmt->fetch()) {
            $this->addLogItemToArray($logMessageString, $includeTrace=false, $logThisObject = null, $ip, $sessionid, $datetime);
        }
        $this->navView = new NavView();

        return $this->logCollection;
    }

    public function addLogItemToDb(LogItemWithIP $logitem) {
        $ipAddress = $logitem->m_ip;
        $sessionid = $logitem->m_sessionid;
        $message = $logitem->m_message;
        $datetime = $logitem->m_dateTime;

        $insertstmt = "INSERT INTO `". self::$table . "` (`pk`, `ip`, `message`, `sessionid`, `datetime`)
				VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->database->prepare($insertstmt);
        if ($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }

        $pk = NULL;
        $bindparamstmt = "'ssss', $pk, $ipAddress, $message, $sessionid, $datetime";
        echo "bindparamstmt: $bindparamstmt<br>";
        //$stmt->bind_param($bindparamstmt);
        $stmt->bind_param('issss', $pk, $ipAddress, $message, $sessionid, $datetime);

        if ($stmt->execute()) {
            $this->message = "Message added to database.";
            return $this->message;
        }
        else {
            $this->message = "Unable to add message to database";
            return $this->message;
        }

        return null;
    }


    /**
     * @param $logMessageString - the message that the user logs
     * @param $includeTrace
     * @param $logObject - the exception object
     * @pa+ram $ipAddress - of the user
     * @param $sessionid - of the user's current session
     * @param $datetime - when the message was logged
     */
    public function addLogItemToArray($logMessageString, $includeTrace, $logObject, $ipAddress, $sessionid, $datetime) {

        $this->logCollection->logWithIp($logMessageString, $includeTrace, $logObject, $ipAddress, $sessionid, $datetime);

    }

}