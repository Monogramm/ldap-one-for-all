<?php


namespace App\Message;

use Symfony\Component\Mime\Email;

class EmailNotification
{
    private $to;

    private $from;

    private $subject;

    private $payload;

    private $template;

    public function __construct(
        string $to,
        string $subject,
        array  $payload,
        string $template,
        ?string $from = null
    ) {
        $this->to = $to;
        $this->subject = $subject;
        $this->payload = $payload;
        $this->template = $template;
        $this->from = $from;
    }

    public function sender(): ?string
    {
        return $this->from;
    }

    public function recipient(): string
    {
        return $this->to;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function template(): string
    {
        return $this->template;
    }
}
