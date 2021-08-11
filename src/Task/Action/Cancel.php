<?php

namespace TaskForce\Task\Action;

class Cancel extends TaskAction {

    protected string $action = 'cancel';
    protected string $actionDescription = 'Отменить задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь отменить задачу
     * @param int $implementorId id исполнителя задачи
     * @param int $userId id проверяемого пользователя
     * @param int $customerId id заказчика задачи
     * @param string $status статус задачи на текущий момент
     * @return bool да\нет
     */
    protected function hasRights(int $implementorId, int $userId, int $customerId, string $status): bool
    {
        return $customerId === $userId && $status === 'new';
    }
}
