<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ip_whitelist extends Model
{
    use HasFactory;
    protected $fillable = ['ip','status'];
}
