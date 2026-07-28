<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountId = $this->parameter('account-id');

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

        foreach ($mediaList as $media) {
            $source = Storage::disk('public')->path('media/' . $media->filename);
            $target = Storage::disk('local')->path('exports/' . $media->original_name);
            
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
