<?php
require_once "vendor/autoload.php";

use TaskForce\Task\Action\TaskAction;
use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;
use \TaskForce\Task\Action\CancelAction;
use \TaskForce\Task\Action\CompleteAction;
use \TaskForce\Task\Action\RefuseAction;
use \TaskForce\Task\Action\RespondAction;


$customer_id = 1;
$implementor_id = 2;


$newTask = new Task(TaskStatusEnum::NEW(), $customer_id);
assert($newTask->customerId == 1, 'хранит id заказчика');

$inWorkTask = new Task(TaskStatusEnum::IN_PROGRESS(), $customer_id, $implementor_id);
assert($inWorkTask->implementorId == 2, 'хранит id исполнителя');

$taskSM = $newTask->getStatefulTask($customer_id);


$cancelAction = new CancelAction(TaskStatusEnum::new(), TaskStatusEnum::cancelled());
$completeAction = new CompleteAction(TaskStatusEnum::in_progress(), TaskStatusEnum::done());
$respondAction = new RespondAction(TaskStatusEnum::new(), TaskStatusEnum::in_progress());
$refuseAction = new RefuseAction(TaskStatusEnum::in_progress(), TaskStatusEnum::failed());

assert($taskSM->can($cancelAction, $newTask, $customer_id) == true, 'проверяет доступное действие');

assert($taskSM->can($refuseAction, $inWorkTask, $customer_id) == false, 'проверяет недоступное действие');

assert($taskSM->getCurrentStatus()->label === 'Новая задача', 'возвращает текущий статус');

assert($taskSM->getNextStatus($refuseAction, $inWorkTask, $implementor_id)->label === 'Задача провалена', 'возвращает следующий статус');

assert($taskSM->getAvailableActions()[0] == $cancelAction, 'возвращает доступные действия');
echo 'Тесты пройдены' . "<br>";
