<?php

namespace model;

//define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'./view/NavView.php');
require_once(__ROOT__.'./model/LogCollection.php');
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
        $preparestmt .= " WHERE logobject IS NOT NULL";
        $preparestmt .= " AND trace IS NOT NULL";
        $preparestmt .= " ORDER BY datetime DESC ";

        $stmt = $this->database->prepare($preparestmt);
        if($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }
        $stmt->execute();
        $stmt->bind_result($pk, $ip, $logMessageString, $trace, $logThisObject, $sessionid, $datetime);
        while ($stmt->fetch()) {
            $this->addLogItemToArray($logMessageString, $trace, $logThisObject, $ip, $sessionid, $datetime);
        }
        $this->navView = new \view\NavView();

        return $this->logCollection;
    }


    /**
     * @param LogItemWithIP $logitem
     * @return bool - true if added to db, false if not
     * @throws \Exception
     */
    public function addLogItemToDb(LogItemWithIP $logitem) {
        $ipAddress = $logitem->m_ip;
        $sessionid = $logitem->m_sessionid;
        $message = $logitem->m_message;
        $datetime = $logitem->m_dateTime;
        $debugbacktrace = $logitem->m_debug_backtrace;
        $logThisObject = $logitem->m_object;
        if ($logitem->m_debug_backtrace != null) {
            $debugbacktrace = serialize($debugbacktrace);
        }

        $insertstmt = "INSERT INTO `". self::$table . "` (`pk`, `ip`, `message`, `trace`, `logobject`, `sessionid`, `datetime`)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->database->prepare($insertstmt);
        if ($stmt === FALSE) {
            throw new \Exception($this->database->error);
        }

        $pk = NULL;

        $stmt->bind_param('issssss', $pk, $ipAddress, $message, $debugbacktrace, $logThisObject, $sessionid, $datetime);

        if ($stmt->execute()) {
            return true;
        }
        else {
            //$errormsg = $stmt->error;
            //throw new \Exception($errormsg);
            return false;
        }

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