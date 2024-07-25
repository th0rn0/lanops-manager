<?php

namespace App\Jobs;

use Storage;
use App\Models\GalleryAlbum;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUploadedImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $album;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GalleryAlbum $album)
    {
        $this->album = $album;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = Storage::disk('gallery-ingest')->allFiles();
        foreach($files as $file) {
            if (in_array(Storage::disk('gallery-ingest')->mimeType($file), ['image/jpeg','image/png'])) {
                ProcessUploadedImage::dispatch($this->album, $file);
            }
        }
    }
}
