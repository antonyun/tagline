<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $media = Media::latest()->paginate(40);
        return Inertia::render('media/Index', [
            'media' => $media,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('media/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,pdf,doc,docx|max:1024000', // max 10MB per file, adjust as needed
        ]);

        $uploadedMedia = [];

        foreach ($request->file('files') as $file) {

            do {
                $filename = Str::orderedUuid();
            } while (Media::where('filename', $filename)->count() > 0);

            $file->storeAs('media', $filename, 'public');

            $media = Media::create([
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file),
                'size' => \File::size($file),
                'md5' => md5_file($file),
                'sha256' => hash_file('sha256', $file),
            ]);

            $media->accounts()->attach($request->account_id);
            $media->categories()->attach($request->category_id);

            $uploadedMedia[] = $media;
        }

        $uploadedCount = count($uploadedMedia);

        return redirect()->route('media.index');
        /* return redirect()->back()->with([
            'flash' => [
                'message' => "Uploaded {$uploadedCount} images",
                'data' => [
                    'media' => $uploadedMedia,
                ],
            ],
        ]);
 */
        /* return response()->json([
            'message' => 'Files uploaded successfully.',
            'media' => $uploadedMedia,
        ], 201); */
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        //
    }
}
