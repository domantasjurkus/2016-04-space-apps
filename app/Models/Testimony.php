<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

final class Testimony extends Model
{

    protected $table = 'testimonies';
    protected $fillable = ['sender_id', 'recipient_id', 'message'];

}