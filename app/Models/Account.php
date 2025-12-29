<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['file_id'];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)->latest();
    }

    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class);
    }

}
