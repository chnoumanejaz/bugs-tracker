<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bugs extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'title',
    //     'description',
    //     'deadline',
    //     'screenshot',
    //     'type',
    //     'status',
    //     'project_id',
    //     'qa_id',
    //     'developer_id',
    // ];

    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

    public function qa()
    {
        return $this->belongsTo(User::class, 'qa_id');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

}
