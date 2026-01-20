<?php

/*
 * Copyright (C) 2022 - 2026, Digitization Academy
 * idigacademy@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AppDeployFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy-files {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles moving, renaming, and replacing files needed per environment settings';

    private Collection $replacements;

    private bool $isDryRun = false;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->isDryRun = (bool) $this->option('dry-run');

            if ($this->isDryRun) {
                $this->warn('DRY RUN MODE: No files will be modified');
            }

            $this->buildReplacementMap();

            $sourceFiles = $this->getSourceFiles();

            if ($sourceFiles->isEmpty()) {
                $this->info('No supervisor templates found. Skipping.');

                return CommandAlias::SUCCESS;
            }

            $this->processFiles($sourceFiles);
            $this->createSupervisorDirectory();

            $this->info('Deployment files processed successfully.');

            return CommandAlias::SUCCESS;

        } catch (Exception $e) {
            $this->error("Deployment failed: {$e->getMessage()}");
            Log::error('AppDeployFiles failed: '.$e->getMessage());

            return CommandAlias::FAILURE;
        }
    }

    /**
     * Build the map of what strings to find and what to replace them with.
     */
    private function buildReplacementMap(): void
    {
        // Your current apps config keys
        $keys = [
            'APP_PATH',
            'APP_ENV',
            'APP_TAG',
            'APP_SERVER_USER',
        ];

        $this->replacements = collect($keys)->mapWithKeys(function ($key) {
            return [$key => $this->resolveConfigurationValue($key)];
        });
    }

    /**
     * Resolve the config value based on the key name.
     */
    private function resolveConfigurationValue(string $key): string
    {
        if (str_starts_with($key, 'APP_')) {
            $configKey = strtolower(str_replace('APP_', '', $key));

            return (string) config('app.'.$configKey);
        }

        return (string) config('config.'.strtolower($key));
    }

    /**
     * Get templates from resources/supervisor.
     */
    private function getSourceFiles(): Collection
    {
        $path = base_path('resources/supervisor');

        if (! File::isDirectory($path)) {
            return collect();
        }

        return collect(File::files($path));
    }

    /**
     * Loop through files and apply replacements.
     */
    private function processFiles(Collection $sourceFiles): void
    {
        $bar = $this->output->createProgressBar($sourceFiles->count());
        $bar->start();

        foreach ($sourceFiles as $file) {
            $targetPath = Storage::path('supervisor/'.$file->getBasename());

            $content = File::get($file->getPathname());

            foreach ($this->replacements as $search => $replace) {
                $content = str_replace($search, $replace, $content);
            }

            if (! $this->isDryRun) {
                $this->ensureDirectoryExists(dirname($targetPath));
                $this->atomicWrite($targetPath, $content);
            } else {
                $this->info("\n[Dry-run] Would write to: {$targetPath}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    /**
     * Ensure the target directory exists.
     */
    private function ensureDirectoryExists(string $dir): void
    {
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
    }

    /**
     * Write file using a temp file to ensure atomicity.
     */
    private function atomicWrite(string $path, string $content): void
    {
        $tempPath = dirname($path).'/.'.basename($path).'.tmp';

        if (File::put($tempPath, $content, true) === false) {
            throw new Exception("Failed to write temp file: {$tempPath}");
        }

        File::move($tempPath, $path);
    }

    /**
     * Create supervisor log directory for the application.
     */
    private function createSupervisorDirectory(): void
    {
        if ($this->isDryRun) {
            $this->info('[Dry-run] Would create /var/log/supervisor directory');

            return;
        }

        $appTag = config('app.tag');
        if ($appTag) {
            $appLogDir = "/var/log/supervisor/{$appTag}";
            exec("sudo mkdir -p {$appLogDir}");
        }
    }
}
