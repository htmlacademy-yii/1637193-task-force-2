<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\ActionOption;
use TaskForce\Task\StatefulInterface;
use TaskForce\Task\TaskStatus;

class CustomerStateMachine extends StateMachine
{
    public \WeakMap $transitions;

    public function __construct(StatefulInterface $document)
    {
        $this->transitions = new \WeakMap();
        //todo вопрос: а надо ли как-то зашить действие "создать задачу"? или это будет потом отдельно?
        $this->transitions[ActionOption::CANCEL()] = ['from' => TaskStatus::NEW(), 'to' => TaskStatus::CANCELLED()];
        $this->transitions[ActionOption::COMPLETE()] = ['from' => TaskStatus::IN_PROGRESS(), 'to' => TaskStatus::DONE()];
        parent::__construct($document, $this->transitions);
    }
}
