<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lead_count extends Model
{
    use HasFactory;
    public $fillable = ['user_id','total_leads'];
}
