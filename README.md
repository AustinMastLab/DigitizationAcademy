# DigitizationAcademy

## Deployment

- Deployments run through **GitHub Actions + Deployer 8** (`.github/workflows/deploy.yml`, `deploy.php`, `deploy/custom.php`).
- **Production:** pushing or merging to `main` calculates the next version, deploys to production (`3.142.169.134`), and creates a GitHub release. Add `[skip deploy]` or `[no deploy]` to the commit message to push without deploying.
- **Development:** pushes to `development` don't deploy. Run the workflow manually (Actions → Build and Deploy → Run workflow → `development`) to deploy to the development server (`3.138.217.206`).
- Assets are built in CI and downloaded as an artifact; the server doesn't build them.
- Releases live under `/data/web/digitizationacademy/releases/<n>`, with `current` pointing at the active one. Both servers serve it with PHP 8.5 (`php8.5-fpm`).
- nginx passes PHP the resolved release path (`fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;` and `fastcgi_param DOCUMENT_ROOT $realpath_root;` after `include fastcgi_params;`), so a new release is served without an OPcache reset. Keep both lines in the site config on each server.
- Queues run under Horizon (Supervisor program `da:da-horizon_00`). After a deploy, check `php artisan horizon:status` and that `horizon:work` processes stay up, not just that Supervisor shows RUNNING.

### Supervisor config

- Templates live in `resources/supervisor/*.conf`. During deploy, `php artisan app:deploy-files --current-path={{current_path}}` renders them into `storage/app/supervisor/` (shared storage), which Supervisor includes on each server. It fills:
  - `{{APP_CURRENT_PATH}}`: `--current-path`, or the app's base path when omitted (`/data/web/digitizationacademy/current` on servers)
  - `{{APP_ENV}}`, `{{APP_TAG}}`, `{{APP_SERVER_USER}}`: `config('app.env')`, `config('app.tag')`, `config('app.server_user')`
- Because Horizon runs from `current`, the rendered file doesn't change between deploys. `artisan:horizon:terminate` runs after `deploy:publish`, and Supervisor restarts Horizon on the new release.
- Run `php artisan app:deploy-files --dry-run` to see where files would be written without changing anything.

## Environment

- The server's `.env` is generated from AWS SSM Parameter Store (`/digitizationacademy/<environment>`) on every deploy by the `env:ssm` task from the shared [deployer-recipes](https://github.com/AustinMastLab/deployer-recipes) package (`set('ssm_app', 'digitizationacademy')` in `deploy.php`). It keeps the last 5 `.env.backup.*` files in `shared/`.
- Manage the parameters from your machine:
  - `vendor/bin/push-env-params digitizationacademy <development|production>` pushes `.env.aws.<environment>` to SSM.
  - `vendor/bin/remove-env-params digitizationacademy <development|production>` deletes every parameter under that path, after you type the environment name to confirm.
- Keep `.env.aws.*` files out of git (covered by `.gitignore`).

# License 
DigitizationAcademy is open-sourced software licensed under GNU General Public License v3.0.

# Translation
Translation by https://translation.io
