<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Guldenbook');

// Project repository
set('repository', 'https://github.com/BramVanBerkel/GuldenBook.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// [Optional] Set gulden_url to download and install a new version of the binaries.
set('gulden_url', null);

// Shared files/dirs between deploys
add('shared_files', [
    '.env',
]);
add('shared_dirs', [
    'binaries',
]);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
host('guldenbook.com')
    ->set('deploy_path', '/var/www/guldenbook');

host('testnet.guldenbook.com')
    ->set('deploy_path', '/var/www/guldenbook-testnet');

after('deploy:update_code', 'build');

// Tasks
task('build', [
    'npm-ci',
    'npm-build',
    'composer-install',
]);

desc('Run npm ci');
task('npm-ci', function() {
    run('cd {{release_path}} && npm ci');
});

desc('Run npm run production');
task('npm-build', function() {
    run('cd {{release_path}} && npm run production');
});

desc('Run composer install');
task('composer-install', function() {
    run('cd {{release_path}} && composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

