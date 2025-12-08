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

use Exception;

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
 * Executes the deploy-files artisan command in the current release path
 */
desc('Deploying files...');
task('artisan:app:deploy-files', function () {
    cd('{{release_or_current_path}}');
    run('php artisan app:deploy-files');
});

/**
 * Set proper file permissions for the application
 * Sets ownership to ubuntu:www-data and clears the Laravel log file
 */
desc('Setting permissions...');
task('set:permissions', function () {
    run('sudo chown -R ubuntu.www-data {{deploy_path}}');
    run('sudo truncate -s 0 {{release_or_current_path}}/storage/logs/laravel.log');
});

/**
 * Publish Sweet Alert 2 assets
 * Executes the sweetalert:publish artisan command
 */
desc('Publish all Sweet Alert 2 assets');
task('artisan:sweetalert:publish', artisan('sweetalert:publish'));

/**
 * Reload Supervisor configuration
 * Executes reread and update commands for Supervisor
 */
desc('Reload Supervisor configuration (config-only update)');
task('supervisor:reload', function () {
    run('sudo supervisorctl reread');
    run('sudo supervisorctl update');
});

/*
 * =============================================================================
 * CI/CD ARTIFACT DEPLOYMENT - CORE FEATURE
 * =============================================================================
 */

desc('Download and extract pre-built assets from GitHub Actions');
task('deploy:ci-artifacts', function () {
    // Environment variables automatically provided by GitHub Actions workflow
    $githubToken = $_ENV['GITHUB_TOKEN'] ?? getenv('GITHUB_TOKEN') ?? '';
    $githubSha = $_ENV['GITHUB_SHA'] ?? getenv('GITHUB_SHA') ?? '';
    $githubRepo = $_ENV['GITHUB_REPO'] ?? getenv('GITHUB_REPO') ?? 'iDigAcademy/DigitizationAcademy';

    // Debug: Show available environment variables for troubleshooting
    writeln('Debug: Checking environment variables...');
    writeln('GITHUB_TOKEN present: '.(! empty($githubToken) ? 'YES' : 'NO'));
    writeln('GITHUB_SHA present: '.(! empty($githubSha) ? 'YES' : 'NO'));
    writeln('GITHUB_REPO: '.$githubRepo);

    // Validate required environment variables
    if (empty($githubToken) || empty($githubSha)) {
        $envVars = array_keys($_ENV);
        $relevantEnvVars = array_filter($envVars, function ($key) {
            return strpos(strtoupper($key), 'GITHUB') !== false;
        });

        $errorMsg = "GITHUB_TOKEN and GITHUB_SHA environment variables are required.\n";
        $errorMsg .= 'Available GitHub-related env vars: '.implode(', ', $relevantEnvVars)."\n";
        $errorMsg .= 'All env vars count: '.count($envVars);

        throw new \Exception($errorMsg);
    }

    // Artifact naming convention: digitizationacademy-{git-sha}
    $artifactName = "digitizationacademy-{$githubSha}";
    writeln("Downloading CI artifact: {$artifactName}");

    // Step 1: Get artifact download URL from GitHub API
    $apiUrl = "https://api.github.com/repos/{$githubRepo}/actions/artifacts";
    $response = runLocally("curl -H 'Authorization: Bearer {$githubToken}' -H 'Accept: application/vnd.github.v3+json' '{$apiUrl}?name={$artifactName}&per_page=1'");
    $artifacts = json_decode($response, true);

    // Validate artifact exists
    if (empty($artifacts['artifacts'])) {
        throw new \Exception("No CI artifact found with name: {$artifactName}");
    }

    $downloadUrl = $artifacts['artifacts'][0]['archive_download_url'];
    cd('{{release_or_current_path}}');

    // Step 2: Download, extract, and deploy CI-built assets
    run("curl -L -H 'Authorization: Bearer {$githubToken}' -H 'Accept: application/vnd.github.v3+json' '{$downloadUrl}' -o artifact.zip");
    run('unzip -o -q artifact.zip');       // Extract artifact quietly, overwrite existing files

    // Debug: Check what was actually extracted
    writeln('Debug: Contents after extraction:');
    run('ls -la');

    // Check if deployment-package directory exists, if not, assume files are in current directory
    $deploymentPackageExists = run('[ -d "deployment-package" ] && echo "true" || echo "false"');
    if (trim($deploymentPackageExists) === 'true') {
        run('rsync -av deployment-package/ ./'); // Sync pre-built assets from deployment-package
        run('rm -rf deployment-package'); // Clean up deployment-package directory
    } else {
        writeln('Debug: No deployment-package directory found, artifacts appear to be extracted directly');
    }

    run('rm -f artifact.zip'); // Cleanup artifact file

    writeln('✅ CI artifacts deployed successfully - No server-side building required!');
});

/*
 * =============================================================================
 * OPCACHE MANAGEMENT
 * =============================================================================
 */

desc('Reset OpCache after deployment');
task('opcache:reset', function () {
    // Method 1: Direct PHP CLI OpCache reset (try first)
    try {
        run('php {{release_or_current_path}}/artisan tinker --execute="if (function_exists(\'opcache_reset\')) { opcache_reset(); echo \'OpCache reset via CLI\'; } else { echo \'OpCache not available via CLI\'; }"');
        writeln('✅ OpCache reset successful via CLI');
    } catch (Exception $e) {
        writeln('⚠️  CLI OpCache reset failed, trying webhook method...');

        // Method 2: Webhook-based OpCache reset (fallback)
        try {
            $webhookToken = $_ENV['OPCACHE_WEBHOOK_TOKEN'] ?? getenv('OPCACHE_WEBHOOK_TOKEN') ?? '';
            if (empty($webhookToken)) {
                throw new Exception('OPCACHE_WEBHOOK_TOKEN not set');
            }

            $environment = get('environment', 'production');  // default to production
            $appUrl = $appUrl = ($environment === 'development' || str_contains($environment, 'dev'))
                ? 'https://devopcache.biospex.org'
                : 'https://opcache.biospex.org';

            $webhookUrl = "{$appUrl}/admin/opcache/reset/{$webhookToken}";
            $response = run("curl -X POST -H 'Content-Type: application/json' '{$webhookUrl}'");

            writeln('✅ OpCache reset successful via webhook');
            writeln('Response: '.$response);
        } catch (Exception $webhookException) {
            writeln('❌ Both CLI and webhook OpCache reset methods failed');
            writeln('CLI Error: '.$e->getMessage());
            writeln('Webhook Error: '.$webhookException->getMessage());

            // Don't fail the deployment, just warn
            writeln('⚠️  Deployment will continue without OpCache reset');
        }
    }
});

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