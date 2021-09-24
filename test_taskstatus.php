<?php

require_once "vendor/autoload.php";

use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;
use \TaskForce\Task\Action\CancelAction;
use \TaskForce\Task\Action\CompleteAction;
use \TaskForce\Task\Action\RefuseAction;
use \TaskForce\Task\Action\RespondAction;

$customer_id = 1; //заказчик
$implementor_id = 2; //исполнитель

$newTask = new Task(TaskStatusEnum::NEW(), $customer_id); //новая задача (для заказчика)
$newTask_implementor = new Task(
    TaskStatusEnum::NEW(), $customer_id, $implementor_id
); //новая задача (логика для исполнителя)
assert($newTask->customerId == 1, 'хранит id заказчика');

$inWorkTask = new Task(TaskStatusEnum::IN_PROGRESS(), $customer_id, $implementor_id);
assert($inWorkTask->implementorId == 2, 'хранит id исполнителя');

//отслеживаем новую задачу для заказчика
try {
    $taskSM = $newTask->getStatefulTask($customer_id);
} catch (\TaskForce\Task\Exceptions\TaskActionException $e) {
    echo 'Не удалось запустить сценарий действий для указанного пользователя: ' . $e->getMessage() . PHP_EOL;
    echo $e . PHP_EOL;
}

//отслеживаем другую новую задачу для исполнителя
try {
    $taskSM_for_implementor = $newTask_implementor->getStatefulTask($implementor_id);
} catch (\TaskForce\Task\Exceptions\TaskActionException $e) {
    echo 'Не удалось запустить сценарий действий для указанного пользователя: ' . $e->getMessage() . PHP_EOL;
    echo $e . PHP_EOL;
}

//объявление действий
$cancelAction = new CancelAction(TaskStatusEnum::new(), TaskStatusEnum::cancelled());
$completeAction = new CompleteAction(TaskStatusEnum::in_progress(), TaskStatusEnum::done());
$respondAction = new RespondAction(TaskStatusEnum::new(), TaskStatusEnum::in_progress());
$refuseAction = new RefuseAction(TaskStatusEnum::in_progress(), TaskStatusEnum::failed());

assert($taskSM->can($cancelAction, $newTask, $customer_id) == true, 'проверяет доступное действие');

assert($taskSM->can($refuseAction, $inWorkTask, $customer_id) == false, 'проверяет недоступное действие');

assert($taskSM->getCurrentStatus()->label === 'Новая задача', 'возвращает текущий статус');

//след. статус после отказа для заказчика - должен срабатывать exception
try {
    $nextStatusAfterRefuse = $taskSM->getNextStatus($refuseAction, $inWorkTask, $customer_id);
    assert($nextStatusAfterRefuse->label === null, 'возвращает следующий статус');
} catch (\TaskForce\Task\Exceptions\TaskStatusException $e) {
    echo 'Не удалось получить след. статус: ' . $e->getMessage() . PHP_EOL;
    echo $e . PHP_EOL;
}

//след. статус после отклика для исполнителя
try {
    $nextStatusAfterRespond = $taskSM_for_implementor->getNextStatus(
        $respondAction,
        $newTask_implementor,
        $implementor_id
    );
    assert($nextStatusAfterRespond?->label === 'Задача в работе', 'возвращает следующий статус');
} catch (\TaskForce\Task\Exceptions\TaskStatusException $e) {
    echo 'Не удалось получить след. статус: ' . $e->getMessage() . PHP_EOL;
    echo $e . PHP_EOL;
}

//доступные действия для заказчика
$availableActions = $taskSM->getAvailableActions();
//для исполнителя
$availableActions_implementor = $taskSM_for_implementor->getAvailableActions();

// проверяем по ключу массива $actions
assert(isset($availableActions[$cancelAction::class]), 'возвращает доступные действия у заказчика');
assert(isset($availableActions_implementor[$respondAction::class]), 'возвращает доступные действия у исполнителя');

// проверка $action - является ли экземпляром определенного класса (но не факт, что проверяемый $action будет первым)
$firstAvailableAction = array_pop($availableActions);
// happy path
assert($firstAvailableAction instanceof $cancelAction, 'первое возможное действие это отмена');
// non-happy path
assert(!($firstAvailableAction instanceof $refuseAction), 'первое возможное действие это НЕ отказ');

echo PHP_EOL . 'Тесты пройдены' . PHP_EOL;
