<?php

namespace LaravelGuru\LaravelFilehandler\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'folder',
        'path',
        'version',
        'type'
    ];

    // Disable Laravel's mass assignment protection
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
