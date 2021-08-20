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

$newTask = new Task(TaskStatusEnum::NEW(), $customer_id);
assert($newTask->customerId == 1, 'хранит id заказчика');

$inWorkTask = new Task(TaskStatusEnum::IN_PROGRESS(), $customer_id, $implementor_id);
assert($inWorkTask->implementorId == 2, 'хранит id исполнителя');

$taskSM = $newTask->getStatefulTask($customer_id);

//объявление действий
$cancelAction = new CancelAction(TaskStatusEnum::new(), TaskStatusEnum::cancelled());
$completeAction = new CompleteAction(TaskStatusEnum::in_progress(), TaskStatusEnum::done());
$respondAction = new RespondAction(TaskStatusEnum::new(), TaskStatusEnum::in_progress());
$refuseAction = new RefuseAction(TaskStatusEnum::in_progress(), TaskStatusEnum::failed());

assert($taskSM->can($cancelAction, $newTask, $customer_id) == true, 'проверяет доступное действие');

assert($taskSM->can($refuseAction, $inWorkTask, $customer_id) == false, 'проверяет недоступное действие');

assert($taskSM->getCurrentStatus()->label === 'Новая задача', 'возвращает текущий статус');

//todo тут нарочно подменил в задаче заказчика на исполнителя. По ТЗ отказаться от задачи может только исполнитель,
// поэтому на заказчика должен срабатывать ассерт. Однако у меня ассерт спокойно проходит дальше. https://skr.sh/s9dRl0YR503
//заранее извиняюсь за var_dump: оставил для демонстрации. Вопрос: почему ассерт не срабатывает? ни в одном из случаев ниже?
// Он должен быть при заказчике 100%
//$nextStatusAfterRefuse = $taskSM->getNextStatus($refuseAction, $inWorkTask, $implementor_id);
$nextStatusAfterRefuse = $taskSM->getNextStatus($refuseAction, $inWorkTask, $customer_id);
var_dump(assert($nextStatusAfterRefuse === 'Задача провалена'));
var_dump($nextStatusAfterRefuse?->label);
var_dump($nextStatusAfterRefuse?->label=== 'Задача провалена');
assert($nextStatusAfterRefuse?->label === 'Задача провалена', 'возвращает следующий статус');
assert(1 === 2, 'возвращает следующий статус');

$availableActions = $taskSM->getAvailableActions();
// проверяем по ключу массива $actions
assert(isset($availableActions[$cancelAction::class]), 'возвращает доступные действия');

// проверка $action - является ли экземпляром определенного класса (но не факт, что проверяемый $action будет первым)
$firstAvailableAction = array_pop($availableActions);
// happy path
assert($firstAvailableAction instanceof $cancelAction, 'первое возможное действие это отмена');
// non-happy path
assert(!($firstAvailableAction instanceof $refuseAction), 'первое возможное действие это НЕ отказ');

echo 'Тесты пройдены' . PHP_EOL;
