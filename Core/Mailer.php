<?php

class Mailer
{
    private $message;

    // $from = 'no-reply@gsb.fr'
    public function __construct($to, $subject, $body, $vars = array(), $from = "team.gsble@gmail.com", $options = array())
    {
        $this->message = Swift_Message::newInstance();
        $this->message->setTo($to);
        $this->message->setFrom($from);
        $this->message->setSubject($subject);

        if ($this->isHTML($body)) {
            $this->message->setBody($body, 'text/html');
            $body = $this->removeHtml($body);
            //$this->message->addPart($body, 'text/plain');
        } else {
            $this->message->setBody($body, 'text/plain');
        }

        /* si cc et bcc set */
        if (isset($options['cc'])) {
            $this->message->setCc($options['cc']);
        }
        if (isset($options['bcc'])) {
            $this->message->setBcc($options['bcc']);
        }
    }

    public function removeHtml($text)
    {
        $text = str_replace('<br/>', '\n', $text);
        // TODO refactorer avec regex
        $text = str_replace('</h1>', "</h1/>\n\n", $text);
        $text = str_replace('</p>', "</p>\n\n", $text);

        return strip_tags($text);
    }

    public function isHTML($string)
    {
        return $string != strip_tags($string);
    }

    public function send()
    {
        $transport = Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, 'ssl');
        $transport->setUsername("team.gsble@gmail.com");
        $transport->setPassword("riveton42");
        $mailer = Swift_Mailer::newInstance($transport);
        $mailer->send($this->message);
    }
} 