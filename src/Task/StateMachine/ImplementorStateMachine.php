<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\ActionOption;
use TaskForce\Task\StatefulInterface;
use TaskForce\Task\TaskStatus;

class ImplementorStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatefulInterface $document)
    { //todo вопрос: надо ли реализовывать функции типа ActionOption::RESPOND() ? если да, то можно пример?
        $this->transitions = new \WeakMap();
        $this->transitions[ActionOption::RESPOND()] = ['from' => TaskStatus::NEW(), 'to' => TaskStatus::IN_PROGRESS()];
        $this->transitions[ActionOption::REFUSE()] = ['from' => TaskStatus::IN_PROGRESS(), 'to' => TaskStatus::FAILED()];
        parent::__construct($document, $this->transitions);
    }
}
