<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['action_plan_id', 'file_name', 'file_path', 'uploaded_by', 'uploaded_at'];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function actionPlan()
    {
        return $this->belongsTo(ActionPlan::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
