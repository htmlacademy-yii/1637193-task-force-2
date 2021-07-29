<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\TaskActionEnum as TaskActionEnum;
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
     * @param TaskActionEnum $action действие, приводящее к смене статуса
     */
    public function apply(TaskActionEnum $action): void
    {
        if ($this->can($action)) {
            $this->document->setStatus($this->getNextStatus($action));
        }
    }

    /**
     * Проверяет, может ли выполнить переход в новое состояние из указанного
     * @param TaskActionEnum $action действие, приводящее к смене статуса
     * @return bool да\нет
     */
    public function can(TaskActionEnum $action): bool
    {
        if (isset($this->transitions[$action])) {
            return $this->transitions[$action]['from']->equals($this->document->getStatus());
        }
        return false;
    }

    /**
     * Возвращает текущий статус задачи
     * @return TaskStatus
     */
    public function getCurrentStatus(): TaskStatus
    {
        return $this->document->getStatus();
    }

    /**
     * Получает статус задачи, в которой она перейдёт после выполнения указанного действия при наличии этого статуса,
     * либо null, если  нового состояния нет
     * @param TaskActionEnum $action действие, приводящее к смене статуса
     * @return TaskStatus|null Новый статус / null
     */
    public function getNextStatus(TaskActionEnum $action): ?TaskStatus
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
        $currentStatus = $this->getCurrentStatus();
        $actions = [];

        foreach ($this->transitions as $action => $status) {
            if ($currentStatus->equals($status['from'])) {
                $actions[] = $action;
            }
        }

        return $actions;
    }


}
