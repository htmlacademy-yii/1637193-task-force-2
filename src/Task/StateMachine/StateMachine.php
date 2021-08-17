<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Action\TaskAction;
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
     * @var array<TaskAction>
     */

    public array $transitions = [];
    public StatusInterface $document;

    /**
     * Применяет новый статус для задачи, при его возможности
     * @param TaskAction $action действие, приводящее к смене статуса
     * @param Task $task объект задачи
     * @param int $currentUserId id проверяемого пользователя
     */
    public function apply(TaskAction $action, $task, $currentUserId): void
    {
        if ($this->can($action, $task, $currentUserId)) {
            $this->document->setStatus($this->getNextStatus($action, $task, $currentUserId));
        }
    }

    /**
     * Проверяет, может ли выполнить переход в новое состояние из указанного
     * @param TaskAction $action действие, приводящее к смене статуса
     * @return bool да\нет
     */
    public function can(TaskAction $action, Task $task, int $currentUserId): bool
    {
        if (!isset($this->transitions[$action::class])) {
            return false;
        }
        if ($action->transitFromStatus === $task->getStatus()) {
            return $action->hasRights($task, $currentUserId);
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
     * @param TaskAction $action действие, приводящее к смене статуса
     * @param Task $task объект задачи
     * @param int $currentUserId id проверяемого пользователя
     * @return TaskStatusEnum|null Новый статус / null
     */
    public function getNextStatus(TaskAction $action, Task $task, int $currentUserId): ?TaskStatusEnum
    {
        if ($this->can($action, $task, $currentUserId)) {
            return $this->transitions[$action::class]->transitToStatus;
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

        foreach ($this->transitions as $stateActionClassName => $stateAction) {
            if ($currentStatus->equals($stateAction->transitFromStatus)) {
                $actions[$stateActionClassName] = $stateAction;
            }
        }

        return $actions;
    }
}
