<?php
require_once "vendor/autoload.php";

require_once "src/Task/StatefulInterface.php";
require_once "src/Task/StateMachine/StateMachine.php";
require_once "src/Task/StateMachine/CustomerStateMachine.php";
require_once "src/Task/StateMachine/ImplementorStateMachine.php";

require_once "src/Task/Task.php";
require_once "src/Task/TaskActionEnum.php";
require_once "src/Task/TaskStatus.php";
require_once "src/Task/UserRole.php";

use TaskForce\Task\TaskActionEnum;
use TaskForce\Task\Task;
use TaskForce\Task\TaskStatus;

$customer_id = 1;
$implementor_id = 2;


$newTask = new Task(TaskStatus::NEW(), $customer_id);
assert($newTask->customerId == 1, 'хранит id заказчика');

$inWorkTask = new Task(TaskStatus::IN_PROGRESS(), $customer_id, $implementor_id);
assert($inWorkTask->implementorId == 2, 'хранит id исполнителя');

$taskSM = $newTask->getStatefulTask($customer_id);
assert($taskSM->can(TaskActionEnum::CANCEL()) == true, 'проверяет доступное действие');
assert($taskSM->can(TaskActionEnum::REFUSE()) == false, 'проверяет недоступное действие');
assert($taskSM->getCurrentStatus()->label == 'Новая задача', 'возвращает текущий статус');
assert($taskSM->getNextStatus(TaskActionEnum::cancel())->label == 'Задача отменена', 'возвращает следующий статус');
assert($taskSM->getAvailableActions()[0] == TaskActionEnum::cancel(), 'возвращает доступные действия');

echo 'Тесты пройдены' . "<br>";
