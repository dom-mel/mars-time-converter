<?php


namespace App\EventListener;

use App\Utils\JsonProblemResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;


class ExceptionListener
{

    public function onKernelException(ExceptionEvent $event):void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof \InvalidArgumentException) {
            $event->setResponse(new JsonProblemResponse('Bad Request', $exception->getMessage(), 400));
        }
        if ($exception instanceof \RuntimeException) {
            $event->setResponse(new JsonProblemResponse('Server error', 500));
        }
    }

}