<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'size',
        'md5',
        'sha256',
        'thumbnail',
    ];

    protected $appends = ['relative_path', 'relative_thumbnail_path'];

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getRelativePathAttribute(): string
    {
        // Assuming files are stored on the public disk in "images" folder
        $name = $this->filename;

        return asset("storage/media/{$name}");
    }

    public function getRelativeThumbnailPathAttribute(): ?string
    {
        // Assuming files are stored on the public disk in "images" folder
        $name = $this->thumbnail;

        return !is_null($name) ? asset("storage/thumbs/{$name}") : null;
    }
}
