<?php
use yii\widgets\ListView;

$this->title = 'Мои задания';

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
?>
<?=$this->render('_menu')?>
<section class="my-list">
    <div class="my-list__wrapper">
        <h1>Мои задания</h1>
        <?php
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'layout' => '{items}<div class="new-task__pagination">{pager}</div>',
            'pager' => [
                'options' => [
                    'class' => 'new-task__pagination-list'
                ],
                'linkContainerOptions' => [
                    'class' => 'pagination__item',
                ],
                'activePageCssClass' => 'pagination__item--current',
                'prevPageCssClass' => '',
                'nextPageCssClass' => '',
                'nextPageLabel' => '',
                'prevPageLabel' => '',
            ],
        ]);
        ?>
    </div>
</section>

