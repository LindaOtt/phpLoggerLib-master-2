<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItem.php");
require_once("./model/LogDAL.php");
require_once("./view/LogView.php");
require_once("./view/NavView.php");


class LogManager {

    private $logCollection;
    private $logView;
    private $logDAL;
    private $mysqli;
    private $view;

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

        /*
        if($this->logView->viewAllIps()) {
            echo $this->showAllIps();
        }
        else if($this->logView->viewOneIp()) {
            echo $this->showOneIp();
        }
        else if($this->logView->viewOneSession()) {
            echo $this->showOneSession();
        }
        else if($this->logView->logMessage()) {
            echo $this->showMsgForm();
        }
        else {
            echo $this->showNavList();
        }
        */

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

}