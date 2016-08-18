<?php
namespace view;
class HTMLView {
    /**
     * Character set of the HTML document for example "utf-8"
     * @var String
     */
    private $charset;
    /**
     * @param String $charset
     */
    public function __construct($charset) {
        $this->charset = $charset;
    }
    /**
     * get a HTML string from title and body
     * @param  String $title
     * @param  String $body
     * @return String (HTML)
     */
    public function getHTMLPage($title, $body) {
        return "<!DOCTYPE html>
      <html>
        <head>
          <meta charset=\"" . $this->charset . "\">
          <link rel='stylesheet' href='..\public\style.css'>
          <title>$title</title>
        </head>
        <body>
          $body
        </body>
      </html>";
    }
}