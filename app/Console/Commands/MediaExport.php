<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:export
        {account-id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export media for specified account ID';

    private function uniqueFilename(string $directory, string $filename): string
    {
        $target = $directory . DIRECTORY_SEPARATOR . $filename;

        if (! File::exists($target)) {
            return $target;
        }

        $info = pathinfo($filename);

        $name = $info['filename'];
        $extension = isset($info['extension'])
            ? '.' . $info['extension']
            : '';

        do {
            $newFilename = sprintf(
                '%s-%s%s',
                $name,
                Str::lower(Str::random(8)),
                $extension
            );

            $target = $directory . DIRECTORY_SEPARATOR . $newFilename;
        } while (File::exists($target));

        return $target;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountId = $this->argument('account-id');

        $account = Account::with('media')->find($accountId);

        if (! $account) {
            $this->error("Account {$accountId} not found.");

            return self::FAILURE;
        }

        Storage::disk('local')->makeDirectory('exports');

        $mediaList = $account->media;

        if ($mediaList->isEmpty()) {
            $this->warn('No media found.');

            return self::SUCCESS;
        }

        $this->info("Exporting {$mediaList->count()} file(s)...");

        $bar = $this->output->createProgressBar($mediaList->count());
        $bar->start();

        $copied = 0;
        $skipped = 0;

        $exportDir = Storage::disk('local')->path('exports');

        foreach ($mediaList as $media) {
            $source = Storage::disk('public')->path('media/' . $media->filename);
            $target = $this->uniqueFilename($exportDir, $media->original_name);
            
            if (File::exists($source)) {
                File::copy($source, $target);
                $copied++;
            } else {
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Copied', $copied],
                ['Skipped (missing)', $skipped],
            ]
        );

        $this->info('Export complete.');

        return self::SUCCESS;
    }
}
