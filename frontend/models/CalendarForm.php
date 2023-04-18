<?php

namespace frontend\models;

use yii\base\Model;

class CalendarForm extends Model
{
    public ?int $month = null;

    public function rules(): array
    {
        return [
            ['month', 'integer'],
        ];
    }
}