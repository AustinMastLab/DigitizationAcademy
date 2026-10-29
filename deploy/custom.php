<?php

/*
 * DIGITIZATION ACADEMY CUSTOM DEPLOYMENT TASKS - Based on Biospex Implementation
 *
 * This file contains custom deployment tasks for the Digitization Academy project.
 *
 * KEY FEATURES:
 * - CI/CD artifact deployment (no server-side building)
 * - Environment-specific configuration uploads
 * - Custom Laravel Artisan commands integration
 *
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

namespace Deployer;

/*
 * =============================================================================
 * CUSTOM LARAVEL ARTISAN TASKS
 * =============================================================================
 */

/**
 * Execute database update queries for the application
 * Changes to the release or current path and runs the update-queries artisan command
 */
desc('Running update queries...');
task('artisan:app:update-queries', function () {
    cd('{{release_or_current_path}}');
    run('php artisan app:update-queries');
});

/**
 * Deploy application files
 * Renders resources/supervisor templates into shared storage, pointing Supervisor at current
 */
desc('Deploying files...');
task('artisan:app:deploy-files', function () {
    cd('{{release_or_current_path}}');
    run('php artisan app:deploy-files --current-path={{current_path}}');
});

/**
 * Run Laravel package discovery
 */
desc('Run Laravel package discovery');
task('artisan:package:discover', function () {
    cd('{{release_or_current_path}}');
    run('php artisan package:discover --ansi');
});

/**
 * Set proper file permissions for the application
 * Sets ownership to ubuntu:www-data and clears the Laravel log file
 */
desc('Setting permissions...');
task('set:permissions', function () {
    run('sudo chown -R ubuntu.www-data {{deploy_path}}');
    run('sudo truncate -s 0 {{release_or_current_path}}/storage/logs/*.log');
});

/**
 * Publish Sweet Alert 2 assets
 * Publishes public/vendor/sweetalert/sweet-alert.js, which the Sweet Alert view loads (public/vendor isn't in git)
 */
desc('Publish the Sweet Alert JavaScript');
task('artisan:alert:publish', artisan('alert:publish --assets --force'));

// deploy:ci-artifacts, deploy:verify-structure and supervisor:reload come from
// deployer-recipes/recipe/ci-artifacts.php (required in deploy.php).

/*
 * =============================================================================
 * ENVIRONMENT MANAGEMENT
 * =============================================================================
 */

/**
 * Clear package discovery cache before running composer operations
 * This prevents conflicts when packages are removed (like Nova)
 */
desc('Clear package discovery cache...');
task('clear:package-cache', function () {
    cd('{{release_or_current_path}}');

    // Remove cached package manifests that might reference removed packages
    run('rm -f bootstrap/cache/packages.php');
    run('rm -f bootstrap/cache/services.php');
    run('rm -f bootstrap/cache/config.php');

    // Clear any cached views that might reference removed packages
    run('rm -rf storage/framework/views/*');
    run('rm -rf storage/framework/cache/data/*');

    writeln('✅ Package discovery cache cleared');
});

desc('Ensure Supervisor log directory exists');
task('supervisor:ensure-log-dir', function () {
    $logDir = '/var/log/supervisor';
    $appTag = get('app_tag', 'app');        // fallback if not set

    // Create main log dir if missing
    run("sudo mkdir -p {$logDir}");

    // Create app-specific log dir (e.g. /var/log/supervisor/digacad)
    $appLogDir = "{$logDir}/{$appTag}";
    run("sudo mkdir -p {$appLogDir}");

    // Optional: set sane permissions
    run("sudo chown root:root {$logDir}");
    run("sudo chmod 755 {$logDir}");
    run("sudo chown ubuntu:ubuntu {$appLogDir}");   // or www-data:www-data
    run("sudo chmod 755 {$appLogDir}");

    writeln("Supervisor log directory ready: {$appLogDir}");
});

/**
 * Publish Filament assets
 */
desc('Publish Filament assets');
task('artisan:filament:assets', function () {
    cd('{{release_or_current_path}}');
    run('php artisan filament:assets');
    writeln('✅ Filament assets published');
});

desc('Optimize Filament resources and assets');
task('artisan:filament:optimize', function () {
    cd('{{release_or_current_path}}');
    run('php artisan filament:optimize --ansi');
    writeln('✅ Filament optimization completed');
});
