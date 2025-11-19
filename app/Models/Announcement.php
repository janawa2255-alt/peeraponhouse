<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'created_by' => 'integer',
    ];
}
