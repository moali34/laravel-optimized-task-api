<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'tags',
        'is_recurring'
    ];

    protected $casts = [
        'tags' => 'array',
        'due_date' => 'date',
        'is_recurring' => 'boolean',
    ];
}