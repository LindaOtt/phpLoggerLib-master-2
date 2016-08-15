<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItemWithIP.php");
require_once("./model/LogDAL.php");
require_once("./view/LogView.php");
require_once("./view/NavView.php");


class LogManager {

    private $logCollection;
    private $logView;
    private $logDAL;
    private $mysqli;
    private $view;
    private $logItemWithIP;

    public function __construct() {


        $this->mysqli = new \mysqli("localhost", "root", "root", "logs");
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $this->logDAL = new \logger\LogDAL($this->mysqli);
        $this->logCollection = $this->logDAL->getLogCollection();
        $this->logView = new LogView($this->logCollection);
        $this->navView = new navView();

    }

    public function handleInput() {

        //Starting session
        $this->navView->handleSession();

        switch(true) {
            case $this->logView->viewAllIps():
                echo $this->showAllIps();
                break;
            case $this->logView->viewOneIp():
                echo $this->showOneIp();
                break;
            case $this->logView->viewOneSession():
                echo $this->showOneSession();
                break;
            case $this->logView->logMessage():
                echo $this->showMsgForm();
                break;
            case $this->logView->submitMessage():
                $this->addMessageToDb();
                break;
            default:
                echo $this->showNavList();
        }

        $this->mysqli->close();
    }

    public function generateOutput() {
        return $this->view;
    }

    public function showAllIps() {
        //Get the log collection
        return $this->logView->getIpView();
    }

    public function showOneIp() {
        return $this->logView->getOneIpView();
    }

    public function showOneSession() {
        return $this->logView->getOneSessionView();
    }

    public function showMsgForm() {
        //Get the form that allows the user to add messages
        return $this->logView->getMsgFormHTML();
    }

    public function showNavList() {
        return $this->logView->getNavList();
    }

    public function addMessageToDb() {

        //Get the message from view
        $message = $this->logView->getMessage();
        $ip = $this->logView->getIpAddress();
        $sessionid = $this->logView->getSessionId();
        $datetime = $this->logView->getTime();
        $this->logItemWithIP = new LogItemWithIP($message, true, null, $ip, $sessionid, $datetime);
        $sentmessage = $this->logDAL->addLogItemToDb($this->logItemWithIP);
        if ($sentmessage != null) {
            $this->logView->showSentMessage($sentmessage);
        }

    }

}