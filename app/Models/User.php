<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

final class User extends Model
{

    protected $fillable = ['name', 'number', 'informed'];

}