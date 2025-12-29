<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CreateThumbnail implements ShouldQueue
{
    use Queueable;

    public Media $media;

    /**
     * Create a new job instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Only create thumbnail for images
        if (!str_starts_with($this->media->mime_type, 'image/')) {
            return;
        }

        $path = "media/{$this->media->filename}";
        $thumb = "thumb_{$this->media->filename}";
        $thumbPath = "thumbs/{$thumb}";

        // Ensure file exists
        if (!Storage::disk('public')->exists($path)) {
            return;
        }

        // Read image
        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path($path));
        
        // Resize to cover 300x300px (crop)
        $image->cover(300, 300);

        // Ensure thumbs dir exists
        Storage::disk('public')->makeDirectory('thumbs');

        // Save thumbnail as JPG
        $image->save(
            Storage::disk('public')->path($thumbPath),
            quality: 85,
            format: 'jpg'
        );

        // Optionally store thumbnail path in DB
        $this->media->update([
            'thumbnail' => $thumb,
        ]);
    }
}
