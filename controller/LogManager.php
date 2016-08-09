<?php
namespace logger;

require_once("./model/LogCollection.php");
require_once("./model/LogItem.php");
require_once("./model/LogDAL.php");
require_once("./view/LogView.php");


class LogManager {

    private $logCollection;
    private $logView;
    private $db;

    public function __construct() {
        $this->db = new \logger\LogDAL();
        $this->logCollection = $this->db->getLogCollection();

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