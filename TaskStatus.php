<?php

class TaskStatus
{
    // Константы с описанием статусов задач
    const STATUS_NEW = 'new_task';
    const STATUS_CANCELLED = 'task_is_cancelled';
    const STATUS_UNDER_CONSIDERATION = 'task_under_consideration_by_the_customer';
    const STATUS_IN_PROGRESS = 'task_in_progress';
    const STATUS_DONE = 'task_is_done';
    const STATUS_FAILED = 'task_is_failed';

    // Константы с описанием возможных действий над задачами
    const ACTION_ADD_NEW = 'add_new_task';
    const ACTION_CANCEL = 'cancel_task';
    const ACTION_RESPOND = 'respond_task';
    const ACTION_COMPLETE = 'complete_task';
    const ACTION_REFUSE = 'refuse_task';
    const ACTION_SELECT_IMPLEMENTER = 'select_implementer';

    protected int $implementerId = 0;
    protected int $customerId = 0;

    /**
     * TaskStatus constructor.
     * @param int $implementerId id исполнителя задачи
     * @param int $customerId id клиента, поставившего задачу
     */
    public function __construct($implementerId, $customerId)
    {
        $this->implementerId = $implementerId;
        $this->customerId = $customerId;
    }

    /**
     * Возвращает «карты» статусов и действий.
     * @return array Карта — это ассоциативный массив, где ключ — внутреннее имя, а значение — названия статуса/действия на русском.
     */
    public function getStatusAndActionsMap(): array
    {
        return [
            self::STATUS_NEW => 'Новая задача',
            self::STATUS_CANCELLED => 'Задача отменена',
            self::STATUS_UNDER_CONSIDERATION => 'Исполнитель на рассмотрении заказчиком', //todo вопрос: не создал ли двоящийся статус?
            self::STATUS_IN_PROGRESS => 'Задача в работе',
            self::STATUS_DONE => 'Задача завершена',
            self::STATUS_FAILED => 'Задача провалена',
            self::ACTION_ADD_NEW => 'Добавить новую задачу',
            self::ACTION_CANCEL => 'Отменить задачу',
            self::ACTION_SELECT_IMPLEMENTER => 'Выбрать исполнителя задачи',
            self::ACTION_RESPOND => 'Откликнуться на задачу',
            self::ACTION_COMPLETE => 'Завершить задачу',
            self::ACTION_REFUSE => 'Отказаться от задачи',
        ];
    }

    /**
     * Получает статус задачи, в которой она перейдёт после выполнения указанного действия
     * @param string $action необходимое действие для смены статуса задачи
     * @return string описание нового статуса задачи
     */
    public function getNextStatus($action): string
    {
        switch ($action) {
            case self::ACTION_ADD_NEW:
                return self::STATUS_NEW;
            case self::ACTION_CANCEL:
                return self::STATUS_CANCELLED;
            case self::ACTION_SELECT_IMPLEMENTER:
                return self::STATUS_UNDER_CONSIDERATION;
            case self::ACTION_RESPOND:
                return self::STATUS_IN_PROGRESS;
            case self::ACTION_COMPLETE:
                return self::STATUS_DONE;
            case self::ACTION_REFUSE:
            default:
                return self::STATUS_FAILED;
        }
    }

    /**
     * получает доступные действия для указанного статуса задачи и пользователя, взаимодействующего с ней
     * @param string $status статус задачи
     * @param int $userId id пользователя
     * @return array массив со списком доступных действий
     */
    public function getAvailableActions($status, $userId): array
    {
        switch ($status) {
            case self::STATUS_NEW:
                if ($userId === $this->customerId) {
                    return [
                        self::ACTION_SELECT_IMPLEMENTER,
                        self::ACTION_CANCEL
                    ];
                }
                return [
                    self::ACTION_RESPOND
                ];
            case self::STATUS_UNDER_CONSIDERATION:
                if ($userId === $this->customerId) {
                    return [
                        self::ACTION_SELECT_IMPLEMENTER
                    ];
                }
                if ($userId === $this->implementerId) {
                    return [
                        self::ACTION_REFUSE
                    ];
                }
                break;
            case self::STATUS_IN_PROGRESS:
                if ($userId === $this->customerId) {
                    return [
                        self::ACTION_COMPLETE
                    ];
                }
                if ($userId === $this->implementerId) {
                    return [
                        self::ACTION_REFUSE
                    ];
                }
                break;
            default:
                return [];
        }
        return [];
    }
}
