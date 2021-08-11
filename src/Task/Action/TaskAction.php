<?php

namespace TaskForce\Task\Action;

abstract class TaskAction
{
    protected string $action;
    protected string $actionDescription;


    /**
     * @return string Возвращает название действия, которое необходимо применить к задаче
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string Возвращает текстовое описание действия к задаче
     */
    public function getActionDescription(): string
    {
        return $this->actionDescription;
    }


    /**
     * Проверяет, имеет ли право указанный пользователь выполнять действия по смене статуса задачи
     * @param int $implementorId id исполнителя задачи
     * @param int $userId id проверяемого пользователя
     * @param int $customerId id заказчика задачи
     * @param string $status статус задачи на текущий момент
     * @return bool да\нет
     */
    abstract protected function hasRights(int $implementorId, int $userId, int $customerId, string $status): bool;
}
