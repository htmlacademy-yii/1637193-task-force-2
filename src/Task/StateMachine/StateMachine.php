<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Task;
use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;
use Spatie\Enum\Enum;

use TaskForce\Task\Action\RespondAction;
use TaskForce\Task\Action\CancelAction;
use TaskForce\Task\Action\RefuseAction;
use TaskForce\Task\Action\CompleteAction;

class StateMachine
{
    /**
     * StateMachine constructor.
     * @param StatusInterface $document
     * @param \WeakMap $transitions
     */
    public function __construct(
        public StatusInterface $document,
        public \WeakMap $transitions
    )
    {
    }

    /**
     * Применяет новый статус для задачи, при его возможности
     * @param CompleteAction|CancelAction|RefuseAction|RespondAction $action действие, приводящее к смене статуса
     * @param Task $task объект задачи
     * @param int $currentUserId id проверяемого пользователя
     */
    public function apply(CompleteAction|CancelAction|RefuseAction|RespondAction $action, $task, $currentUserId): void
    {
        if ($this->can($action, $task, $currentUserId)) {
            $this->document->setStatus($this->getNextStatus($action, $task, $currentUserId));
        }
    }

    /**
     * Проверяет, может ли выполнить переход в новое состояние из указанного
     * @param CompleteAction|CancelAction|RefuseAction|RespondAction $action действие, приводящее к смене статуса
     * @return bool да\нет
     */
    public function can(CompleteAction|CancelAction|RefuseAction|RespondAction $action, Task $task, int $currentUserId): bool
    {
        if (isset($this->transitions[$action])) {
            return $action->hasRights($task, $currentUserId);
            //return $this->transitions[$action]['from']->equals($this->document->getStatus());
        }
        return false;
    }

    /**
     * Возвращает текущий статус задачи
     * @return TaskStatusEnum
     */
    public function getCurrentStatus(): TaskStatusEnum
    {
        return $this->document->getStatus();
    }

    /**
     * Получает статус задачи, в которой она перейдёт после выполнения указанного действия при наличии этого статуса,
     * либо null, если  нового состояния нет
     * @param CompleteAction|CancelAction|RefuseAction|RespondAction $action действие, приводящее к смене статуса
     * @param Task $task объект задачи
     * @param int $currentUserId id проверяемого пользователя
     * @return TaskStatusEnum|null Новый статус / null
     */
    public function getNextStatus(CompleteAction|CancelAction|RefuseAction|RespondAction $action, Task $task, int $currentUserId):
    ?TaskStatusEnum
    {
        if ($this->can($action, $task, $currentUserId)) {
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
            if ($currentStatus === $status['from']) {
                $actions[] = $action;
            }
//            if ($currentStatus->equals($status['from'])) {
//                $actions[] = $action;
//            }
        }

        return $actions;
    }


}
