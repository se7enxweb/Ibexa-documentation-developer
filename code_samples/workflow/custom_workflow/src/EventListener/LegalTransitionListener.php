<?php

declare(strict_types=1);

namespace App\EventListener;

use Ibexa\Contracts\AdminUi\Notification\TranslatableNotificationHandlerInterface as NotificationInterface;
use Ibexa\Contracts\Workflow\Event\Action\AbstractTransitionWorkflowActionListener;
use Symfony\Component\Workflow\Event\TransitionEvent;

class LegalTransitionListener extends AbstractTransitionWorkflowActionListener
{
    private NotificationInterface $notificationHandler;

    public function __construct(NotificationInterface $notificationHandler)
    {
        $this->notificationHandler = $notificationHandler;
    }

    public function getIdentifier(): string
    {
        return 'legal_transition_action';
    }

    public function onWorkflowEvent(TransitionEvent $event): void
    {
        $metadata = $this->getActionMetadata($event->getWorkflow(), $event->getTransition());
        $message = $metadata['data']['message'];

        $this->notificationHandler->info(
            $message,
            [],
            'domain'
        );

        $this->setResult($event, true);
    }
}
