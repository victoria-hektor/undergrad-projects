<?php
/**
 * file to send a mailer each time a message is sent or received on server
 * logs in log files
 * @sendMail returns: either SENT or RETRIEVED or ERROR message
 *
 **/

class Mailer
{
    private $php_mailer;
    private $logger;

    public function __construct()
    {
        $this->php_mailer = null;
        $this->logger = null;
    }

    public function setPHPMailer($php_mailer)
    {
        $this->php_mailer = $php_mailer;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function sendMail(string $xml_string, int $type): void
    {
        $mail = $this->php_mailer;
        $logger = $this->logger;

        $mail_sent['message'] = "Yes! Mail sent successfully! " . $xml_string;

        if ($type === 0)
        {
            $mail->Subject = 'New telemetry message SENT to server!';
        }
        elseif ($type === 1)
        {
            $mail->Subject = 'New telemetry message RETRIEVED from server!';
        }

        try
        {
            $mail->Body = $xml_string;
            $mail->send();

        } catch (exception $e)
        {
            $mail_sent['mail-sent'] = "Mail not sent! Error: " . $e;
            $logger->notice('Mail sent?', $mail_sent);
        }


    }


}
