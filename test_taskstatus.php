<?php
require_once 'TaskStatus.php';
//use TaskStatus;

$task_status = new TaskStatus(1, 2);
assert($task_status->getNextStatus(TaskStatus::ACTION_ADD_NEW) === TaskStatus::STATUS_NEW, 'провал');
assert($task_status->getNextStatus(TaskStatus::ACTION_CANCEL) === TaskStatus::STATUS_CANCELLED, 'провал');
assert($task_status->getNextStatus(TaskStatus::ACTION_RESPOND) === TaskStatus::STATUS_IN_PROGRESS, 'провал');
assert($task_status->getNextStatus(TaskStatus::ACTION_COMPLETE) === TaskStatus::STATUS_DONE, 'провал');
assert($task_status->getNextStatus(TaskStatus::ACTION_REFUSE) === TaskStatus::STATUS_FAILED, 'провал');
echo 'Тесты пройдены';
