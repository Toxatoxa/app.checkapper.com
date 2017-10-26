<?php

namespace App\Jobs;

use App\FilmThumb;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SaveThumbJob extends Job implements ShouldQueue
{

//    public $timeout = 120;

    use InteractsWithQueue, SerializesModels;

    protected $thumbId;

    /**
     * SaveThumbJob constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->thumbId = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $thumb = FilmThumb::with('film')->find($this->thumbId);
        if (!$thumb->remote_100) {
            return;
        }

        $sizes = [];
        foreach (FilmThumb::$allSizes as $size) {
            if (!in_array($size, $thumb->local_sizes)) {
                array_push($sizes, $size);
            }
        }

        if ($sizes) {
            $localSizes = $thumb->local_sizes;
            foreach ($sizes as $size) {
                $path = $thumb->getPathToFile($size);
                Storage::disk('p')->delete($path);
                Storage::disk('p')->put($path, file_get_contents($thumb->getUrlBySize($size)));
                array_push($localSizes, $size);
            }
            $thumb->local_sizes = $localSizes;
        }

        $thumb->start_updating_at = null;
        $thumb->save();
    }
}
