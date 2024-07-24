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

class ProcessUploadedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $album;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GalleryAlbum $album, $file)
    {
        $this->album = $album;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (in_array(Storage::disk('gallery-ingest')->mimeType($this->file), ['image/jpeg','image/png'])) {
            $this->album->addMediaFromDisk($this->file, 'gallery-ingest')->toMediaCollection('images');
        }
    }
}
