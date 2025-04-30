<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityDesign extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'grade',
        'duration',
        'objectives',
        'materials',
        'preparation',
        'warm_up',
        'presentation',
        'practice',
        'production',
        'assessment',
        'notes',
        'status'
    ];

    protected $casts = [
        'objectives' => 'array',
        'materials' => 'array',
        'preparation' => 'array',
        'warm_up' => 'array',
        'presentation' => 'array',
        'practice' => 'array',
        'production' => 'array',
        'assessment' => 'array',
        'notes' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStagesAttribute()
    {
        return [
            'warm_up' => $this->warm_up,
            'presentation' => $this->presentation,
            'practice' => $this->practice,
            'production' => $this->production
        ];
    }
} 