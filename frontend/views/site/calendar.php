<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\CalendarForm $model */
/** @var yii\data\ArrayDataProvider $dataProvider */
$this->title = 'My Calendar';

function getHolidays(int $month): array
{
    $year = date('Y');
    $numDays = date('t', strtotime("$year-$month-01"));
    $holidays = [];
    $formatter = Yii::$app->formatter;

    for ($i = 1; $i <= $numDays; ++$i) {
        $timestamp = strtotime("$year-$month-$i");
        $date = $formatter->asDate($timestamp, 'php:D d-m');
        $dayOfWeek = date('N', $timestamp);
        if ($dayOfWeek == 6 || $dayOfWeek == 7) {
            $holidays[] = [
                'dayOfWeek' => $dayOfWeek,
                'date' => $date,
            ];
        }
    }

    return $holidays;
}

$months = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December'
];

$columns = ['_'];

$holidays = getHolidays($model->month);
foreach ($holidays as $holiday) {
    $disabledSunday = 7 == $holiday['dayOfWeek'] && ! Yii::$app->user->can('updateCalendar');

    $header = '<button class="btn btn-primary btn-block" onclick="openModal()" '.($disabledSunday ? 'disabled' : '').'>'.$holiday['date'].'</button>';

    $columns[] = ['class' => \yii\grid\Column::class, 'header' => $header];
}

Modal::begin(['id' => 'modal']);
echo 'Content here';
Modal::end();

?>

<h2>Calendar</h2>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'form', 'method' => 'get']) ?>

            <?= $form->field($model, 'month')->dropDownList($months) ?>

            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block']) ?>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ]) ?>
        </div>
    </div>
</div>

<script>
    function openModal()
    {
        $('#modal').modal('show');
    }
</script>
