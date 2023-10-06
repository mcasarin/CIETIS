<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

class MyPHPMailer extends PHPMailer {
    /**
     * myPHPMailer constructor.
     *
     * @param bool|null $exceptions
     * @param string    $body A default HTML message body
     */
    public function __construct($exceptions, $body = '') {
        //Don't forget to do this or other things may not be set correctly!
        parent::__construct($exceptions);
        //Set a default 'From' address
        $this->setLanguage('pt-br', BASE_URL.'/include/PHPMailer/language/');
        $this->setFrom('naoresponda@cietis.etwas.com.br','CIETIS');
        //Send via SMTP
        $this->isSMTP();
        //Equivalent to setting `Host`, `Port` and `SMTPSecure` all at once
        $this->Host = 'cietis.etwas.com.br';
        //Set an HTML and plain-text body, import relative image references
        $this->Port = '465';        //Sets the default SMTP server port
        $this->SMTPAuth = true;
        $this->Username = 'naoresponda@cietis.etwas.com.br';
        $this->Password = 'CORcel%5665!';
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->msgHTML($body, './images/');
        //Show debug output
        // $this->SMTPDebug = SMTP::DEBUG_SERVER;
        //Inject a new debug output handler
        $this->Debugoutput = static function ($str, $level) {
            echo "Debug level $level; message: $str\n";
        };
    }
    
    //Extend the send function
    public function send() {
        $r = parent::send();
        return $r;
    }
}