<?php


namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    private const SUPPORTED_LOCALES = ['en', 'fr'];

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES);
        $request->setLocale($locale);
    }
}
