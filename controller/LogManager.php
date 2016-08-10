<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItem.php");
require_once("./model/LogDAL.php");
require_once("./view/LogView.php");


class LogManager {

    private $logCollection;
    private $logView;
    private $logDAL;
    private $mysqli;

    public function __construct() {
        session_start();

        $this->mysqli = new \mysqli("localhost", "root", "root", "logs");
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $this->logDAL = new \logger\LogDAL($this->mysqli);
        $this->logCollection = $this->logDAL->getLogCollection();
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
        $this->mysqli->close();
    }

    public function showAllIps() {
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