<?php

/*
 * Copyright (c) 2022. Digitization Academy
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

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Storage;

/**
 * Class AppFileDeployment
 */
class AppDeployFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles moving, renaming, and replacing files needed per environment settings';

    private string $resPath;

    private string $supPath;

    private Collection $apps;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->resPath = base_path('resources');
        $this->supPath = Storage::path('supervisor');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setAppsConfigs();

        $supFiles = File::files($this->resPath.'/supervisor');
        $supTargets = collect($supFiles)->map(function ($file) {
            $target = $this->supPath.'/'.$file->getBaseName();
            if (File::exists($target)) {
                File::delete($target);
            }
            File::copy($file->getPathname(), $target);

            return $target;
        });

        $this->apps->each(function ($search) use ($supTargets) {
            $replace = $this->configureReplace($search);
            $supTargets->each(function ($file) use ($search, $replace) {
                exec("sed -i 's*$search*$replace*g' $file");
            });
        });

        $this->createSupervisorDirectory();
    }

    /**
     * @return false|\Illuminate\Config\Repository|mixed|string
     */
    private function configureReplace($search): mixed
    {
        if (str_starts_with($search, 'APP_')) {
            $value = strtolower(str_replace('APP_', '', $search));

            return config('app.'.$value);
        }

        return config('config.'.strtolower($search));
    }

    /**
     * Set search and replace arrays.
     */
    private function setAppsConfigs()
    {
        $this->apps = collect([
            'APP_PATH',
            'APP_ENV',
            'APP_TAG',
            'SERVER_USER',
        ]);
    }

    /**
     * Create supervisor log directory for the application.
     *
     * Creates a subdirectory under /var/log/supervisor using the application tag
     * from configuration. Uses sudo to ensure proper permissions.
     */
    private function createSupervisorDirectory(): void
    {
        $logDir = '/var/log/supervisor';
        $appTag = config('app.tag');
        $appLogDir = "{$logDir}/{$appTag}";
        exec("sudo mkdir -p {$appLogDir}");
    }
}
