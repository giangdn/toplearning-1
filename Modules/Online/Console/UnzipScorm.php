<?php

namespace Modules\Online\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourseActivityScorm as ActivityScorm;

class UnzipScorm extends Command
{
    protected $signature = 'online:unzip-scorm';

    protected $description = 'Unzip scorm actitvity. cron chay 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $query = ActivityScorm::from('el_online_course_activity_scorms AS a')
            ->whereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_scorms AS b')
                    ->whereColumn('b.origin_path', '=', 'a.path')
                    ->whereIn('status', [0, 1]);
            });

        if (!$query->exists()) {
            return;
        }

        $zip = new \ZipArchive();
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $today = date('Y/m/d');

        $rows = $query->limit(1)->get();
        foreach ($rows as $row) {
            $row->scorm->update([
                'status' => 3,
            ]);

            if (!$storage->exists($row->path)) {

                $row->scorm->update([
                    'status' => 0,
                    'error' => 'File not exists.',
                ]);

                continue;
            }

            $this->info('Unzip path ' . $row->path);

            $res = $zip->open($storage->path($row->path));

            if ($res === true) {
                $unzip_folder = $today . '/scorm/' . Str::random(10);
                $scorm_folder = $storage->path($unzip_folder);

                if (!$storage->exists($unzip_folder)) {
                    \File::makeDirectory($scorm_folder, 0777, true);
                }

                $zip->extractTo($scorm_folder);
                $zip->close();

                $index_file = $this->scanIndexFile($scorm_folder);

                if (!$index_file) {
                    $row->scorm->update([
                        'status' => 0,
                        'error' => 'Cannot find index file.',
                    ]);

                    \File::deleteDirectory($scorm_folder);

                    continue;
                }

                $row->scorm->update([
                    'origin_path' => $row->path,
                    'unzip_path' => $unzip_folder,
                    'index_file' => $index_file,
                    'status' => 1,
                ]);

                $this->info("Unziped to folder " . $unzip_folder);

            } else {
                $row->scorm->update([
                    'status' => 0,
                    'error' => 'Cannot not open file.',
                ]);
            }
        }
    }

    protected function scanIndexFile($scorm_folder) {
        $scan_disk = [
            'index_scorm.html',
            'index_lms.html',
            'index.html',
            'index.htm',
            'indexAPI.html',
        ];
        $path = '';

        if (\File::isDirectory($scorm_folder.'/scormdriver')) {
            $files = \File::files($scorm_folder.'/scormdriver');
            $path = 'scormdriver';
        }elseif (\File::isDirectory($scorm_folder.'/res')){
            $files = \File::files($scorm_folder.'/res');
            $path = 'res';
        }else{
            $files = \File::files($scorm_folder);
        }

        foreach ($files as $file) {
            if (in_array($file->getBasename(), $scan_disk)) {
                return $path ? ($path.'/'.$file->getBasename()) : $file->getBasename();
            }
        }

        return false;
    }
}
