<?php
namespace TaskForce\Task;

use Spatie\Enum\Enum;

/**
 * @method static self ADD_NEW()
 * @method static self CANCEL()
 * @method static self RESPOND()
 * @method static self COMPLETE()
 * @method static self REFUSE()
 */
class ActionOption extends \Spatie\Enum\Enum
{
    // Константы с описанием возможных действий над задачами
    //todo вопрос: оставить константы? видел внутри enum, что там заглавные переводит в мелкие буквы при вызове функций из статусов
    const ADD_NEW = 'add_new_task';
    const CANCEL = 'cancel_task';
    const RESPOND = 'respond_task';
    const COMPLETE = 'complete_task';
    const REFUSE = 'refuse_task';

    /**
     * Возвращает «карты» действий.
     * @return array Карта — это ассоциативный массив, где ключ — внутреннее имя, а значение — названия действия на русском.
     */
    protected static function labels(): array
    {
        return [
            self::ADD_NEW => 'Добавить новую задачу',
            self::CANCEL => 'Отменить задачу',
            self::RESPOND => 'Откликнуться на задачу',
            self::COMPLETE => 'Завершить задачу',
            self::REFUSE => 'Отказаться от задачи',
        ];
    }
}
