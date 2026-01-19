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
    run('sudo truncate -s 0 {{release_or_current_path}}/storage/logs/*.log');
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

    // Validate required environment variables
    if (empty($githubToken) || empty($githubSha)) {
        throw new \Exception('GITHUB_TOKEN and GITHUB_SHA environment variables are required.');
    }

    // Artifact naming convention: digitizationacademy-{git-sha}
    $artifactName = "digitizationacademy-{$githubSha}";
    writeln("Downloading CI artifact: {$artifactName}");

    // Step 1: Get artifact download URL from GitHub API
    $apiUrl = "https://api.github.com/repos/{$githubRepo}/actions/artifacts";
    $response = runLocally("curl -H 'Authorization: Bearer {$githubToken}' -H 'Accept: application/vnd.github.v3+json' '{$apiUrl}?name={$artifactName}&per_page=1'");
    $artifacts = json_decode($response, true);

    if (empty($artifacts['artifacts'])) {
        throw new \Exception("No CI artifact found with name: {$artifactName}");
    }

    $downloadUrl = $artifacts['artifacts'][0]['archive_download_url'];
    cd('{{release_or_current_path}}');

    // Step 2: Download and extract
    run("curl -L -H 'Authorization: Bearer {$githubToken}' -H 'Accept: application/vnd.github.v3+json' '{$downloadUrl}' -o artifact.zip");
    run('unzip -o -q artifact.zip');

    // Step 3: Biospex-style Nesting Correction
    $nestLevelCmd = run('find . -type d -name "deployment-package" -printf "%p\\n" | wc -l');
    $nests = (int) trim($nestLevelCmd);

    if ($nests > 0) {
        $innermost = run('find . -type d -name "deployment-package" | sort -r | head -1');
        $innermost = trim($innermost);
        if (! empty($innermost)) {
            run("rsync -av '{$innermost}/' ./");
            run('find . -type d -name "deployment-package" -exec rm -rf {} +');
        }
    }

    run('rm -f artifact.zip');
    writeln('✅ CI artifacts deployed successfully (Flat structure ensured)');
});

/*
 * =============================================================================
 * OPCACHE & ENVIRONMENT MANAGEMENT
 * =============================================================================
 */

desc('Reset OpCache after deployment');
task('opcache:reset', function () {
    $token = $_ENV['OPCACHE_WEBHOOK_TOKEN'] ?? getenv('OPCACHE_WEBHOOK_TOKEN') ?? '';

    if (empty($token)) {
        writeln('⚠️  Skipping OpCache reset: OPCACHE_WEBHOOK_TOKEN not set');

        return;
    }

    $environment = get('environment', 'production');
    $domain = ($environment === 'production') ? 'digitizationacademy.org' : 'dev.digitizationacademy.org';

    // Static URL
    $url = "https://{$domain}/opcache/reset";

    try {
        writeln("Triggering OpCache reset via application route: {$url}");
        // Send token as POST data
        $response = run("curl -X POST -sL -k -d 'token={$token}' '{$url}'");

        if (str_contains($response, 'successful')) {
            writeln('✅ OpCache reset successful');
        } else {
            throw new \Exception("Response: {$response}");
        }
    } catch (\Exception $e) {
        writeln('❌ OpCache reset failed: '.$e->getMessage());
    }
});

desc('Generate .env from AWS SSM Parameter Store');
task('env:ssm', function () {
    $appName = 'digitizationacademy';
    $environment = currentHost()->get('environment') ?? 'development';
    $remoteUser = get('remote_user');
    $homeDir = "/home/{$remoteUser}";

    // Assumes the 'generate-env' script exists in the home directory on the server
    $cmd = "cd {$homeDir} && ./generate-env {$appName} {$environment}";

    writeln("Running: {$cmd}");
    run($cmd);
})->once();

desc('Verify flat deployment structure');
task('deploy:verify-structure', function () {
    $nestCheck = run('find {{release_path}} -type d -name "deployment-package" | wc -l');
    if ((int) trim($nestCheck) > 0) {
        throw new \Exception("Nesting detected post-deploy: {$nestCheck} dirs. Check CI artifact.");
    }
    writeln('✅ Deployment structure verified: flat and clean');
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
