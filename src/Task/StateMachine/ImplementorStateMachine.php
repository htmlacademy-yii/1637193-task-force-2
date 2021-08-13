<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Action\RespondAction;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class ImplementorStateMachine extends StateMachine
{
    public function __construct(StatusInterface $document)
    {
        $this->document = $document;
        $respondAction = new RespondAction(TaskStatusEnum::new(), TaskStatusEnum::in_progress());
        $this->transitions[$respondAction::class] = $respondAction;
    }
}
