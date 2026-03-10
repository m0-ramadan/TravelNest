<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantModel extends Model
{
    use HasFactory;

    protected $table = 'important_models';

    protected $fillable = [
        'important_text',
        'model',
    ];
}