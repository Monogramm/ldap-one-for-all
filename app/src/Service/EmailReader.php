<?php


namespace App\Service;

use Carbon\Carbon;
use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Mail\Storage\Imap;

class EmailReader
{
    private $mail;

    public function __construct(iterable $mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return array
     *
     * @psalm-return list<mixed>
     */
    public function getMessagesBySubjectExpAndDateTimeUTC(
        string $exp,
        \DateTimeInterface $fromDateUTC
    ): array {
        $messages = [];
        foreach ($this->mail as $message) {
            try {
                $messageDateUTC = Carbon::createFromFormat(DATE_RFC2822, $message->date)
                    ->setTimezone('UTC');
            } catch (\Exception $e) {
                $messageDateUTC = Carbon::createFromFormat(DATE_RFC2822, substr_replace($message->date, '', 31))
                    ->setTimezone('UTC');
            }

            if (($messageDateUTC >= $fromDateUTC) && preg_match($exp, $message->subject)) {
                $messages[] = $message;
            }
        }

        return $messages;
    }
}
