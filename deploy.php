<?php

/*
 * DIGITIZATION ACADEMY CI/CD DEPLOYMENT CONFIGURATION - Based on Biospex Implementation
 *
 * USAGE:
 * - Automatic deployment via GitHub Actions (recommended)
 * - Manual deployment: dep deploy production|development
 *
 * HOW IT WORKS:
 * 1. GitHub Actions builds assets and creates artifacts
 * 2. Deployer downloads artifacts (no server-side building)
 * 3. Environment-specific configuration
 * 4. Automatic cleanup (node_modules removed)
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

require 'recipe/laravel.php';
require 'deploy/custom.php';

// Deployment Configuration
set('repository', 'https://github.com/AustinMastLab/DigitizationAcademy.git');
set('base_path', '/data/web');
set('remote_user', 'ubuntu');
set('php_fpm_version', '8.3');
set('ssh_multiplexing', true);
set('writable_mode', 'chmod');
set('keep_releases', 3);  // Keep only 3 recent releases

// Use sudo for cleanup to prevent "Directory not empty" or permission errors
set('cleanup_use_sudo', true);

// Shared Files (persisted across deployments)
set('shared_files', [
    '.env',                        // Environment configuration
    'public/mix-manifest.json',    // Laravel Mix manifest for asset versioning
]);

// Shared Directories (persisted across deployments)
set('shared_dirs', [
    'storage',          // Laravel storage (logs, cache, uploads)
    'public/css',       // Compiled CSS files
    'public/js',        // Compiled JavaScript files
    'public/fonts',     // Web fonts
    'public/images',    // Static images
    'public/svg',       // SVG assets
    'public/vendor',    // Vendor assets (Filament, etc.)
]);

// Files/Directories to Remove After Deployment
set('clear_paths', [
    'node_modules',     // Remove after CI artifacts are deployed
    'deployment-package', // Remove any residual nesting dirs
]);

// Determine if the local identity file exists (for manual deployments)
$localKey = '/home/ubuntu/.ssh/biospexaws.pem';
$hasLocalKey = file_exists($localKey);

// Server Configurations
// Production
host('production')
    ->set('hostname', '3.142.169.134')
    ->set('deploy_path', '{{base_path}}/digitizationacademy')
    ->set('branch', 'main')
    ->set('environment', 'production')
    ->set('app_tag', 'digacad');

if ($hasLocalKey) {
    host('production')->set('identity_file', $localKey);
}

// Development
host('development')
    ->set('hostname', '3.138.217.206')
    ->set('deploy_path', '{{base_path}}/digitizationacademy')
    ->set('branch', 'development')
    ->set('environment', 'development')
    ->set('app_tag', 'digacad');

if ($hasLocalKey) {
    host('development')->set('identity_file', $localKey);
}

/*
 * DEPLOYMENT TASK SEQUENCE - CI/CD Implementation
 *
 * This sequence eliminates server-side building by using CI artifacts.
 * Each task is executed in order with proper error handling.
 */
desc('Deploys your project using CI/CD artifacts');
task('deploy', [
    // Phase 1: Preparation
    'deploy:prepare',           // Create release directory and setup structure

    // Phase 1.5: Ensure .env from SSM is ready
    'env:ssm',

    // Phase 2: Dependencies & Assets
    'deploy:vendors',          // Install PHP Composer dependencies safely
    'deploy:ci-artifacts',     // Download & extract pre-built assets from GitHub Actions

    // Phase 3: Laravel Setup
    'artisan:storage:link',    // Create symbolic link for storage directory
    'artisan:package:discover', // Run package discovery
    'artisan:horizon:publish', // Publish Laravel Horizon assets
    'artisan:sweetalert:publish', // Publish Sweet Alert assets
    'artisan:filament:assets',
    'artisan:app:deploy-files', // Custom app deployment files

    // Phase 4: Database & Updates
    'artisan:migrate',         // Run database migrations
    'artisan:app:update-queries', // Run custom database updates

    // Phase 5: Cache Optimization
    'artisan:optimize:clear',  // Clear all Laravel caches
    'artisan:cache:clear',     // Clear application cache
    'artisan:config:cache',    // Cache configuration files
    'artisan:route:cache',     // Cache route definitions
    'artisan:view:cache',      // Cache Blade templates
    'artisan:event:cache',     // Cache event listeners
    'artisan:optimize',        // Run Laravel optimization
    'artisan:filament:optimize',   // Optimize Filament resources and assets

    // Phase 7: Domain-Specific Supervisor Management
    'supervisor:reload', // Update configs only
    'artisan:horizon:terminate',
    'artisan:queue:restart',

    // Phase 8: Finalization
    'set:permissions',
    'deploy:clear_paths',      // Remove unnecessary files/directories
    'deploy:publish',          // <--- SYMLINK SWITCHES HERE

    // Phase 6: OpCache Management (Now moved after publish)
    'opcache:reset',           // <--- NOW IT WILL FIND THE ROUTE

    'deploy:verify-structure', // Verify flat structure post-deploy
]);

// Hooks
after('deploy:failed', 'deploy:unlock');
