<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class ImplementorStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatusInterface $document)
    {
        $this->transitions = new \WeakMap();
        $this->transitions[TaskActionEnum::respond()] = ['from' => TaskStatusEnum::new(), 'to' => TaskStatusEnum::in_progress()];
        $this->transitions[TaskActionEnum::refuse()] = ['from' => TaskStatusEnum::in_progress(), 'to' => TaskStatusEnum::failed()];
        parent::__construct($document, $this->transitions);
    }
}
