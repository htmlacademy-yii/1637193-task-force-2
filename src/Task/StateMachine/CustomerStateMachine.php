<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\StatefulInterface;
use TaskForce\Task\TaskStatus;

class CustomerStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatefulInterface $document)
    {
        $this->transitions = new \WeakMap();
        $this->transitions[TaskActionEnum::cancel()] = ['from' => TaskStatus::new(), 'to' => TaskStatus::cancelled()];
        $this->transitions[TaskActionEnum::complete()] = ['from' => TaskStatus::in_progress(), 'to' => TaskStatus::done()];
        parent::__construct($document, $this->transitions);
    }
}
