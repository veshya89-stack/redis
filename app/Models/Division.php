<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['kode', 'nama'];

    public function actionPlans()
    {
        return $this->belongsToMany(ActionPlan::class, 'action_plan_division');
    }
}
