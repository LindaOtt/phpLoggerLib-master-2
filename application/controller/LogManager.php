<?php
namespace controller;

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/model/LogCollection.php');
require_once(__ROOT__.'/model/LogItemWithIP.php');
require_once(__ROOT__.'/model/LogDAL.php');
require_once(__ROOT__.'/view/LogView.php');
require_once(__ROOT__.'/view/NavView.php');


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

        $this->logDAL = new \model\LogDAL($this->mysqli);
        $this->logCollection = $this->logDAL->getLogCollection();
        $this->logView = new \view\LogView($this->logCollection);
        $this->navView = new \view\navView();

    }


    /**
     * Checks which user case is active,
     * sets state in view
     */
    public function handleInput() {

        //Starting session
        $this->navView->handleSession();

        switch(true) {
            case $this->logView->viewAllIps():
                //$this->showAllIps;
                $this->logView->setShowState("viewAllIps");
                break;
            case $this->logView->viewOneIp():
                //$this->showOneIp();
                $this->logView->setShowState("viewOneIp");
                break;
            case $this->logView->viewOneSession():
                //echo $this->showOneSession();
                $this->logView->setShowState("showOneSession");
                break;
            case $this->logView->logMessage():
                //echo $this->showMsgForm();
                $this->logView->setShowState("showMessageForm");
                break;
            case $this->logView->submitMessage():
                if ($this->addMessageToDb() == true) {
                    $this->logView->setShowState("addedMessage");
                }
                else {
                    $this->logView->setShowState("failedMessage");
                }
                break;
            default:
                $this->logView->setShowState("showNavList");
        }

        $this->mysqli->close();
    }


    /**
     * @return bool true if added to db | error message if not added to db
     */
    public function addMessageToDb() {

        //Get the message from view
        $message = $this->logView->getMessage();
        $ip = $this->logView->getIpAddress();
        $sessionid = $this->logView->getSessionId();
        $datetime = $this->logView->getTime();
        $this->logItemWithIP = new \model\LogItemWithIP($message, true, null, $ip, $sessionid, $datetime);
        $sentmessage = $this->logDAL->addLogItemToDb($this->logItemWithIP);
        if ($sentmessage == true) {
            return true;
        }
        else {
            return false;
        }

    }


    /**
     * @return string
     */
    public function generateOutput() {
        $this->view = $this->logView->getHTML();
        return $this->view;
    }

}