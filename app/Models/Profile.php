<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $casts = [
        'has_face_photo' => 'boolean',
        'has_body_photo' => 'boolean',
        'has_dick_photo' => 'boolean',
        'has_anus_photo' => 'boolean',
        'has_cum_photo' => 'boolean',
        'has_had_sex' => 'boolean',       
        'extra' => 'array',
    ];

    protected $fillable = [
        'account_id',
        'legacy_id',
        'name',
        'name_latin',
        'ethnicity',
        'location',
        'age',
        'year_of_birth',
        'date_of_birth',
        'height',
        'weight',
        'measurement_size',
        'position',
        'preferences',
        'has_face_photo',
        'has_body_photo',
        'has_dick_photo',
        'has_anus_photo',
        'has_cum_photo',
        'has_had_sex',
        'phone_number',
        'social_profiles',
        'comment',
        'extra',
    ];

    public function thumbnail()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
