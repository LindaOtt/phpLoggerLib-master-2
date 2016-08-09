<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItem.php");

require_once("./view/LogView.php");
require_once("./view/NavView.php");

class LogManager {

    private $logCollection;
    private $logView;
    private $navView;
    private $ipAddress;


    public function __construct() {
        $this->logCollection = new LogCollection();
        $this->navView = new NavView();

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

        //public function logWithIP($string, $trace = false, $object = null, $ip) {
        //Temporary log collection, code before data!
        $this->logCollection->logWithIP($logMessageString1, $includeTrace1, $logObject1, $this->ipAddress);
        $this->logCollection->logWithIP($logMessageString2, $includeTrace2, $logObject2, $this->ipAddress);
        $this->logCollection->logWithIP($logMessageString3, $includeTrace3, $logObject3, $this->ipAddress);

        $this->logView = new LogView($this->logCollection);

    }

    public function handleInput() {
        if($this->logView->viewAllIps()) {
            echo $this->showAllIps();
        }
        else if($this->logView->logMessage()) {
            echo $this->showMsgForm();
        }
        else {
            echo $this->showNavList();
        }
    }

    public function showAllIps() {
        //
        //Get the log collection
        return $this->logView->getIpView();
    }

    public function showMsgForm() {
        //Get the form that allows the user to add messages
        return $this->logView->getMsgFormHTML();
    }

    public function showNavList() {
        return $this->logView->getNavList();
    }

}