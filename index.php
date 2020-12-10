<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Task.php';

$task = new Task(1, 2, 'new');
?>
<div>
    <h3>Карта статусов</h3>
    <?php
    echo '<pre>';
    var_dump(Task::STATUSES_LIST);
    echo '</pre>';
    ?>
</div>
<div>
    <h3>Карта действий</h3>
    <?php
    echo '<pre>';
    var_dump(Task::ACTIONS_LIST);
    echo '</pre>';
    ?>
</div>
<div>
    <h3>Получения статуса задания после выполнения указанного действия</h3>
    <?php
    assert($task->getNextStatus(Task::ACTION_CANCEL) == Task::STATUS_NEW, '!Cancel action exception!');
    assert($task->getNextStatus(Task::ACTION_CANCEL) == Task::STATUS_CANCELED, '!Cancel action exception!');
    ?>
</div>
<div>
    <h3>Получения доступных действий для указанного статуса</h3>
    <?php
    echo 'Статус новый';
    echo '<pre>';
    var_dump($task->getAvailableActions());
    echo '</pre>';
    ?>
</div>
