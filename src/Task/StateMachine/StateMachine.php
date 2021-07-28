<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\ActionOption as ActionOption;
use TaskForce\Task\StatefulInterface;
use TaskForce\Task\TaskStatus as TaskStatus;

class StateMachine
{
    /**
     * StateMachine constructor.
     * @param StatefulInterface $document
     * @param \WeakMap $transitions
     */
    public function __construct(
        public StatefulInterface $document,
        public \WeakMap $transitions
    )
    {
    }

    /**
     * Применяет новый статус для задачи, при его возможности
     * @param ActionOption $action действие, приводящее к смене статуса
     */
    public function apply(ActionOption $action): void
    {
        if ($this->can($action)) {
            $this->document->setFiniteState($this->getNextStatus($action));
        }
    }

    /**
     * Проверяет, может ли выполнить переход в новое состояние из указанного
     * @param ActionOption $action действие, приводящее к смене статуса
     * @return bool да\нет
     */
    public function can(ActionOption $action): bool
    {
        if (isset($this->transitions[$action])) {
            return $this->transitions[$action]['from']->equals($this->document->getFiniteState());
        }
        return false;
    }

    /**
     * Возвращает текущий статус задачи
     * @return TaskStatus
     */
    public function getCurrentState(): TaskStatus
    {
        return $this->document->getFiniteState();
    }

    /**
     * Получает статус задачи, в которой она перейдёт после выполнения указанного действия при наличии этого статуса,
     * либо null, если  нового состояния нет
     * @param ActionOption $action действие, приводящее к смене статуса
     * @return TaskStatus|null Новый статус / null
     */
    public function getNextStatus(ActionOption $action): ?TaskStatus
    {
        if ($this->can($action)) {
            return $this->transitions[$action]['to'];
        }

        return null;
    }

    /**
     * Выдает список новых действий для текущего статуса задачи
     * @return array массив с элементами возможных новых действий для текущего статуса задачи либо пустой массив при их отсутствии
     */
    public function getAvailableActions(): array
    {
        $currentState = $this->getCurrentState();
        $actions = [];

        foreach ($this->transitions as $action => $states) {
            if ($currentState->equals($states['from'])) {
                $actions[] = $action;
            }
        }

        return $actions;
    }


}
