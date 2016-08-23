<?php

namespace view;

/**
 * Class LogView
 * @package view
 */
class LogView {

	private $log;
    private static $viewIpsURL = "viewallips";
    private static $viewOneIpURL = "viewip";
    private static $viewOneSessionURL = "sessionid";
    private static $addMsgURL = "addmsg";
    private static $submitPostID = "submit";
    private static $MessageID = "message";
    private $showState;
    private $title;
    private $view;

	public function __construct(\model\LogCollection $log) {
		$this->log = $log;
        $this->showState = "showNavList";
	}


    /**
     * @param $state
     */
    public function setShowState($state) {
	    $this->showState = $state;
    }

	/**
	* @param boolean $doDumpSuperGlobals
	* @return string HTML 
	*/
	public function getDebugData($doDumpSuperGlobals = false) {
		
		
		if ($doDumpSuperGlobals) {
			$superGlobals = $this->dumpSuperGlobals();
		} else {
			$superGlobals = "";
		}
		
		$debugItems = "";
		foreach ($this->log->getList() as $item) {
			$debugItems .= $this->showDebugItem($item);
		}
		$dumps = "
			<div>
				<hr/>
				<h2>Debug</h2>
				<table>
					<tr>
						<td>$superGlobals</td>
				   		<td>
				   			<h3>Debug Items</h3>
				   			<ol>
				   				$debugItems
				   			</ol>
					 	</td>
					</tr>
			    </table>
		    </div>";
		return $dumps;
	}

	/**
	* @return string HTML 
	*/
	private function dumpSuperGlobals() {
		$dumps = $this->arrayDump($_GET, "GET");
		$dumps .= $this->arrayDump($_POST, "POST");
		
		$dumps .= $this->arrayDump($_COOKIE, "COOKIES");
		if (isset($_SESSION)) {
			$dumps .= $this->arrayDump($_SESSION, "SESSION");
		}
		$dumps .= $this->arrayDump($_SERVER, "SERVER");

		return $dumps;
	}
	
	/**
	* @param LogItem $item
	* @return string HTML 
	*/
	private function showDebugItem(\model\LogItem $item) {
		
		if ($item->m_debug_backtrace != null) {
			$debug = "<h4>Trace:</h4>
					 <ul>";
			foreach ($item->m_debug_backtrace AS $key => $row) {

				//the two topmost items are part of the logger
				//skip those
				if ($key < 2) { 
					continue;
				}
				$key = $key - 2;
				$debug .= "<li> $key " . LogItem::cleanFilePath($row['file']) . " Line : " . $row["line"] .  "</li>";
			}
			$debug .= "</ul>";
		} else {
			$debug = "";
		}
		
		if ($item->m_object != null)
			$object = print_r($item->m_object, true);
		else 
			$object = "";
		list($usec, $sec) = explode(" ", microtime());
        date_default_timezone_set('Europe/Stockholm');
		$date = date("Y-m-d H:i:s", $sec);
		$ret =  "<li>
					<Strong>$item->m_message </strong> $item->m_calledFrom 
					<div style='font-size:small'>$date $usec</div>
					<pre>$object</pre>
					
					$debug
					
				</li>";
				
		return $ret;
	}
	
	
	/**
	* @return string HTML 
	*/
	private function arrayDump($array, $title) {
		$ret = "<h3>$title</h3>
		
				<ul>";
		foreach ($array as $key => $value) {
			$value = htmlspecialchars($value);
			$ret .= "<li>$key => [$value]</li>";
		}
		$ret .= "</ul>";
		return $ret;
	}


    /**
     * @return bool
     */
    public function viewAllIps() {
        return isset($_GET[self::$viewIpsURL]) == true;
    }

    /**
     * @return bool
     */
    public function viewOneIp() {
        return isset($_GET[self::$viewOneIpURL]) == true;
    }

    /**
     * @return bool
     */
    public function viewOneSession() {
        return isset($_GET[self::$viewOneSessionURL]) == true;
    }

    /**
     * @return bool
     */
    public function logMessage() {
        return isset($_GET[self::$addMsgURL]) == true;
    }

    /**
     * @return bool
     */
    public function submitMessage() {
        return isset($_POST[self::$submitPostID]) == true;
    }

    /**
     * @return string
     */
    public function getIpView() {

        $sessionsPerIpList = $this->log->getSortedIpList();
        $ret = "<h2>All ips</h2>
        <table>
        <tr>
        <th>Ip address</th>
        <th>Number of sessions</th>
        </tr>";
        foreach ($sessionsPerIpList as $key=> $value) {

            $ret .=
                "<tr>
            <td><a href='?viewip=".$key."'>".  $key ."</a></td>
            <td>". $value ."</td>
            </tr>";
        }

        $ret .= "</table>";
        return $ret;

    }


    /**
     * @return string
     */
    public function getOneIpView() {
        $viewedIP=$_GET[self::$viewOneIpURL];

        $ret = "<h2>IP: $viewedIP</h2>
        <table border='1'>
        <tr>
        <th>Ip address</th>
        <th>Session ID</th>
        <th>Time</th>
        </tr>";

        foreach ($this->log->getList() as $item) {
            date_default_timezone_set('Europe/Stockholm');
            if ($viewedIP==$item->m_ip) {
            $ret .=
                "<tr>
            <td>". $item->m_ip ."</td>
            <td><a href='?sessionid=".$item->m_sessionid."'>". $item->m_sessionid ."</a></td>
            <td>". $item->m_dateTime ."</td>
            </tr>";
            }
        }

        $ret .= "</table>";

        return $ret;
    }


    /**
     * @return string
     */
    public function getOneSessionView() {
        $viewedSession=$_GET[self::$viewOneSessionURL];
        $ret = "<h2>Session: $viewedSession</h2>
        <table border='1'>
        <tr>
        <th>Ip address</th>
        <th>Session ID</th>
        <th>Trace</th>
        <th>Log Item</th>
        <th>Time</th>
        </tr>";
        foreach ($this->log->getList() as $item) {
            date_default_timezone_set('Europe/Stockholm');
            if ($viewedSession==$item->m_sessionid) {

                if ($item->m_debug_backtrace != null) {
                    $debug = "
					 <ul>";
                    foreach ($item->m_debug_backtrace AS $key => $row) {

                        //the two topmost items are part of the logger
                        //skip those
                        if ($key < 2) {
                            continue;
                        }
                        $key = $key - 2;
                        $debug .= "<li> $key " . \model\LogItem::cleanFilePath($row['file']) . " Line : " . $row["line"] .  "</li>";
                    }
                    $debug .= "</ul>";
                } else {
                    $debug = "";
                }


                $object = print_r($item->m_object, true);
                $object = stripslashes(preg_replace('/\\\{2,}/', '', $object));
                $ret .=
                    "<tr>
                    <td>". $item->m_ip ."</td>
                    <td>". $item->m_sessionid ."</td>
                    <td>". $debug ."</td>
                    <td><div class='objecttext'><pre>". $object ."</pre></div></td>
                    <td>". $item->m_dateTime ."</td>
                    </tr>";
            }
        }
        $ret .= "</table>";
        $ret .= "<a href='?'>Back</a>";
        return $ret;
    }


    /**
     * @return string
     */
    public function getMsgFormHTML() {
        $ret = "<h2>Add a log message</h2>
        <form method='post' action='?'>
        <label for='message'>Message: </label><input type='text' id='message' name='message'>
        <input type='submit' value='Submit' name='submit'>
        </form>";
        return $ret;
    }


    /**
     * @return mixed
     */
    public function getIpAddress() {
        $ip = $_SERVER['SERVER_ADDR'];
        return $ip;
    }


    /**
     * @return string
     */
    public function getSessionId() {
        $sessionid = session_id();
        return $sessionid;
    }

    /**
     * @return string
     */
    public function getNavList() {
        $ret = "<h2>Pick something:</h2>
        <ul>
        <li><a href='?". self::$viewIpsURL ."'>Show all ip addresses with log traces</li>
        <li><a href='?". self::$addMsgURL . "'>Add a message</li>
        </ul>";
        return $ret;
    }


    /**
     * @return mixed
     */
    public function getMessage() {
        return $_POST[self::$MessageID];
    }


    /**
     * @return bool|string
     */
    public function getTime() {
        list($usec, $sec) = explode(" ", microtime());
        date_default_timezone_set('Europe/Stockholm');
        $date = date("Y-m-d H:i:s", $sec);
        return $date;
    }


    /**
     * @return string
     */
    public function getLinkToMainPage() {
        return "<p><a href=''>Back</a></p>";
    }


    /**
     * @param $message
     * @return string
     */
    public function showSentMessage($message) {
        $ret ="<p>$message</p>
        <p>" . $this->getLinkToMainPage() ."</p>";
        return $ret;
    }


    /**
     * @return string
     */
    public function getHTML() {

        switch($this->showState) {
            case "showNavList":
                $this->view = $this->getNavList();
                break;
            case "viewAllIps":
                $this->view = $this->getIpView();
                break;
            case "viewOneIp":
                $this->view = $this->getOneIpView();
                break;
            case "showOneSession":
                $this->view = $this->getOneSessionView();
                break;
            case "showMessageForm":
                $this->view = $this->getMsgFormHTML();
                break;
            case "addedMessage":
                $message = "Message was added to the database";
                $this->view = $this->showSentMessage($message);
                break;
            case "failedMessage":
                $message = "Unable to add message to the database.";
                $this->view = $this->showSentMessage($message);
                break;
            default:
                $this->view = $this->getNavList();
        }
        return $this->view;
    }

}
