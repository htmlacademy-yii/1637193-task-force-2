<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\StatefulInterface;
use TaskForce\Task\TaskStatus;

class ImplementorStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatefulInterface $document)
    { //todo вопрос: надо ли реализовывать функции типа TaskActionEnum::respond() ? если да, то можно пример?
        $this->transitions = new \WeakMap();
        $this->transitions[TaskActionEnum::respond()] = ['from' => TaskStatus::new(), 'to' => TaskStatus::in_progress()];
        $this->transitions[TaskActionEnum::refuse()] = ['from' => TaskStatus::in_progress(), 'to' => TaskStatus::failed()];
        parent::__construct($document, $this->transitions);
    }
}
