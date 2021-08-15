<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Action\CancelAction;
use TaskForce\Task\Action\CompleteAction;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class CustomerStateMachine extends StateMachine
{
    public function __construct(StatusInterface $document)
    {
        $this->document = $document;
        $cancelAction = new CancelAction(TaskStatusEnum::new(), TaskStatusEnum::cancelled());
        $this->transitions[$cancelAction::class] = $cancelAction;

        $completeAction = new CompleteAction(TaskStatusEnum::in_progress(), TaskStatusEnum::done());
        $this->transitions[$completeAction::class] = $completeAction;
    }
}
