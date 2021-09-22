<?php

namespace TaskForce\Task\StateMachine;

use TaskForce\Task\Action\TaskAction;
use TaskForce\Task\Exceptions\TaskActionException;
use TaskForce\Task\Exceptions\TaskStatusException;
use TaskForce\Task\Task;
use TaskForce\Task\StatusInterface;
use TaskForce\Task\TaskStatusEnum;

class StateMachine
{
    /**
     * @var array<TaskAction>
     */
    public array $transitions = [];
    public StatusInterface $document;

    /**
     * Применяет новый статус для задачи, при его возможности применения
     * @param TaskAction $action Действие, приводящее к смене статуса
     * @param Task $task Объект задачи
     * @param int $currentUserId Id проверяемого пользователя
     * @throws TaskStatusException
     */
    public function apply(TaskAction $action, Task $task, int $currentUserId): void
    {
        if ($this->can($action, $task, $currentUserId)) {
            $this->document->setStatus($this->getNextStatus($action, $task, $currentUserId));
        }
    }

    /**
     * Проверяет, может ли выполнить переход в новое состояние из указанного
     * @param TaskAction $action Действие, приводящее к смене статуса
     * @return bool Да\нет
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
     * @return TaskStatusEnum Текущий статус задачи
     */
    public function getCurrentStatus(): TaskStatusEnum
    {
        return $this->document->getStatus();
    }

    /**
     * Получает статус задачи, в которой она перейдёт после выполнения указанного действия при наличии этого статуса,
     * либо null, если нового состояния нет
     * @param TaskAction $action Действие, приводящее к смене статуса
     * @param Task $task Объект задачи
     * @param int $currentUserId Id проверяемого пользователя
     * @return TaskStatusEnum Новый статус / null
     * @throws TaskStatusException Исключение при отсутствии доступного статуса задачи
     */
    public function getNextStatus(TaskAction $action, Task $task, int $currentUserId): ?TaskStatusEnum
    {
        if ($this->can($action, $task, $currentUserId)) {
            return $this->transitions[$action::class]->transitToStatus;
        }

        throw new TaskStatusException('Нет доступного статуса задачи для перехода из текущего');
    }

    /**
     * Выдает список новых действий для текущего статуса задачи
     * @return array Массив с элементами возможных новых действий для текущего статуса задачи либо пустой массив при их отсутствии
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
