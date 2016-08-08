<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItem.php");

require_once("./view/LogView.php");

class LogManager {

    private $logCollection;
    private $logView;


    public function __construct() {
        $this->logCollection = new LogCollection();

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
        $this->logCollection->log($logMessageString1, $includeTrace1, $logObject1);
        $this->logCollection->log($logMessageString2, $includeTrace2, $logObject2);
        $this->logCollection->log($logMessageString3, $includeTrace3, $logObject3);

        $this->logView = new LogView($this->logCollection);

    }

    public function handleInput() {
        if($this->logView->viewAllIps()) {
            echo $this->showAllIps();
        }
        else {
            echo $this->showMessageForm();
        }
    }

    public function showAllIps() {
        //Get the log collection
        return $this->logView->getIpView();
    }

    public function showMessageForm() {
        //Get the form that allows the user to add messages
        return $this->logView->getMessageHTML();
    }

}