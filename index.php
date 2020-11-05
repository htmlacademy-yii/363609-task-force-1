<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Task.php';

$task = new Task(1, 2);
?>
<div>
<h3>Карта статусов</h3>
<?php
echo '<pre>';
var_dump($task->getAllStatus());
echo '</pre>';
?>
</div>
<div>
<h3>Карта действий</h3>
<?php
echo '<pre>';
var_dump($task->getAllActions());
echo '</pre>';
?>
</div>
<div>
<h3>Получения статуса задания после выполнения указанного действия</h3>
<?php
assert($task->getNextStatus(Task::ACTION_CANCEL, Task::STATUS_NEW) == Task::STATUS_NEW, '!Cancel action exception!');
assert($task->getNextStatus(Task::ACTION_CANCEL, Task::STATUS_NEW) == Task::STATUS_CANCELED, '!Cancel action exception!');

assert($task->getNextStatus(Task::ACTION_RESPOND, Task::STATUS_NEW) == Task::STATUS_IN_WORK, '!In work action exception!');
assert($task->getNextStatus(Task::ACTION_RESPOND, Task::STATUS_NEW) == Task::STATUS_COMPLETED, '!In work action exception!');

assert($task->getNextStatus(Task::ACTION_DONE, Task::STATUS_IN_WORK) == Task::STATUS_COMPLETED, '!Complete action exception!');
assert($task->getNextStatus(Task::ACTION_DONE, Task::STATUS_IN_WORK) == Task::STATUS_CANCELED, '!Complete action exception!');

assert($task->getNextStatus(Task::ACTION_REFUSE, Task::STATUS_IN_WORK) == Task::STATUS_FAILED, '!Failed action exception!');
assert($task->getNextStatus(Task::ACTION_REFUSE, Task::STATUS_IN_WORK) == Task::STATUS_COMPLETED, '!Failed action exception!');
?>
</div>
<div>
<h3>Получения доступных действий для указанного статуса</h3>
<?php
echo 'Статус новый';
echo '<pre>';
var_dump($task->getAvailAction(Task::STATUS_NEW));
echo '</pre>';

echo 'Статус в работе';
echo '<pre>';
var_dump($task->getAvailAction(Task::STATUS_IN_WORK));
echo '</pre>';

echo 'Другие статусы';
echo '<pre>';
var_dump($task->getAvailAction(Task::STATUS_CANCELED));
echo '</pre>';
echo '<pre>';
var_dump($task->getAvailAction(Task::ACTION_RESPOND));
echo '</pre>';
?>
</div>
