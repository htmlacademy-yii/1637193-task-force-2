<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class CustomerStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatusInterface $document)
    {
        $this->transitions = new \WeakMap();
        $this->transitions[TaskActionEnum::cancel()] = ['from' => TaskStatusEnum::new(), 'to' => TaskStatusEnum::cancelled()];
        $this->transitions[TaskActionEnum::complete()] = ['from' => TaskStatusEnum::in_progress(), 'to' => TaskStatusEnum::done()];
        parent::__construct($document, $this->transitions);
    }
}
