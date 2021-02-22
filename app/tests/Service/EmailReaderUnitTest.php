<?php

namespace App\Tests;

use App\Service\EmailReader;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Zend\Mail\Storage\Folder;
use Zend\Mail\Storage\Imap;

class EmailReaderUnitTest extends TestCase
{
    public function testReader()
    {
        $message1 = new \stdClass();
        $message1->date = Carbon::now()->subMinutes(20)->format(DATE_RFC2822);
        $message1->subject = 'Test Case 1';

        $message2 = new \stdClass();
        $message2->date = Carbon::now()->subMinutes(10)->format(DATE_RFC2822);
        $message2->subject = 'Test Case 2';

        $message3 = new \stdClass();
        $message3->date = Carbon::now()->subMinutes(40)->format(DATE_RFC2822);
        $message3->subject = 'Test Case 3';

        $message4 = new \stdClass();
        $message4->date = Carbon::now()->subMinutes(20)->format(DATE_RFC2822);
        $message4->subject = 'Test Case 4';

        $message5 = new \stdClass();
        $message5->date = Carbon::now()->subMinutes(20)->format(DATE_RFC2822) .'CET';
        $message5->subject = 'Test Case 5';

        $emailReader = new EmailReader([$message1, $message2, $message3, $message4, $message5]);

        $fromDate = Carbon::now()->subMinutes(40)->setTimezone('UTC');
        $messages = $emailReader->getMessagesBySubjectExpAndDateTimeUTC('/Case 4/m', $fromDate);

        $this->assertIsArray($messages);
        $this->assertEquals($messages[0], $message4);
    }
}
