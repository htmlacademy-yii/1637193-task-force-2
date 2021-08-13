<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Action\CancelAction;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class CustomerStateMachine extends StateMachine
{
    public function __construct(StatusInterface $document)
    {
        $this->document = $document;
        $respondAction = new CancelAction(TaskStatusEnum::new(), TaskStatusEnum::cancelled());
        $this->transitions[$respondAction::class] = $respondAction;
    }
}
