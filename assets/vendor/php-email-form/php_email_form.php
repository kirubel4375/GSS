<?php

class PHP_Email_Form
{
    public $to;
    public $from_name;
    public $from_email;
    public $subject;
    public $message;
    public $headers;
    public $smtp;

    function __construct()
    {
        $this->to = '';
        $this->from_name = '';
        $this->from_email = '';
        $this->subject = '';
        $this->message = '';
        $this->headers = "MIME-Version: 1.0" . "\r\n";
        $this->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $this->headers .= "From: " . $this->from_name . " <" . $this->from_email . ">" . "\r\n";
        $this->smtp = array();
    }

    function add_message($message, $label = '')
    {
        if ($label) {
            $this->message .= "<strong>" . $label . ":</strong> " . $message . "<br>";
        } else {
            $this->message .= $message . "<br>";
        }
    }

    function send()
    {
        if (empty($this->to) || empty($this->from_name) || empty($this->from_email) || empty($this->subject) || empty($this->message)) {
            return 'Error: Missing required fields';
        }

        if (!filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
            return 'Error: Invalid email format';
        }

        $to = $this->to;
        $subject = $this->subject;
        $message = $this->message;
        $headers = $this->headers;

        // Check if SMTP is configured
        if (!empty($this->smtp) && !empty($this->smtp['host']) && !empty($this->smtp['username']) && !empty($this->smtp['password']) && !empty($this->smtp['port'])) {
            $smtp_host = $this->smtp['host'];
            $smtp_username = $this->smtp['username'];
            $smtp_password = $this->smtp['password'];
            $smtp_port = $this->smtp['port'];

            // Set SMTP headers
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers .= "X-Priority: 1\r\n";
            $headers .= "Return-Path: $this->from_email\r\n";
            $headers .= "Reply-To: $this->from_email\r\n";

            // Send email using SMTP
            if (mail($to, $subject, $message, $headers)) {
                return 'Success: Email sent successfully';
            } else {
                return 'Error: Failed to send email';
            }
        } else {
            // Send email using mail() function
            if (mail($to, $subject, $message, $headers)) {
                return 'Success: Email sent successfully';
            } else {
                return 'Error: Failed to send email';
            }
        }
    }
}

?>
