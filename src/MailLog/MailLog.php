<?php

namespace Mcms\Core\MailLog;


use Mcms\Core\Exceptions\InvalidMailMessageException;
use Mcms\Core\Models\MailLog as MailLogModel;

class MailLog
{
    /**
     * @param $event
     */
    public function handle($event)
    {
        try {
            $message = $event->message;
            $body = $message->getBody();
            $subject = $message->getSubject();
            $to = key($message->getTo());
            $from = key($message->getFrom());
        } catch (InvalidMailMessageException $e) {
            return;
        }
//        @$bcc=key($message->getBcc());
        MailLogModel::create([
            'to' => $to,
            'from' => $from,
            'subject' => $subject,
            'body' => $body,
            'read' => false,
            'attempt' => 1,
            'sended_at' => date('Y-m-d H:i:s', time())
        ]);


    }
}