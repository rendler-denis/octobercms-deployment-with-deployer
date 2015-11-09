<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 02/11/2015
 * Project: octo
 *
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */
require_once 'vendor/autoload.php';
require_once 'recipes/common.php';
require_once 'server_config.php';

set('repository', 'git@github.com:ADD_YOUR_REPO_HERE');

set('keep_releases', 3);

set('shared_dirs', [
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/cms/cache',
    'storage/logs',
    'storage/temp',
    'vendor',
]);

set('shared_files', ['.env']);

set('writable_dirs', [
	'storage', 
	'storage/framework', 
	'storage/logs',
	'storage/temp',
	'storage/cms/cache',
	'storage/framework/cache', 
]);
/**
*  This is the path where our OctoberCMS is available on the deployment machine.
*  It is used to create the Git tags before deployment
*/
env('code_path', '/var/www/YOUR_OCTOBERCMS_CODE_PATH_HERE');

/**
* Instruct OctoberCMS to create a mirror folder in the web/ dir
*/
task('deploy:symlink:web', function() {
    $deployPath = env('deploy_path');

    cd($deployPath);

    run('cd {{release_path}} && php artisan october:mirror web/');
});

/**
*  After OctoberCMS finishes creating the mirror folder copy the .htaccess as well
*/
task('deploy:cp-htaccess', function() {
   run('cp {{release_path}}/.htaccess {{release_path}}/web'); 
});

/**
* Instruct deployer to create a Git tag before deployment
*/
task('deploy:tag-deployment', function() {
    $codePath = env('code_path');
    $time     = date('d/m/YTH-i-s');

    cd($codePath);

    runLocally("git tag -a -m 'Deployment of version {$time}' '{$time}'  && git push origin --tags");
});

/**
*  Main deployment task which creates the deployment scenario
*/
task('deploy-test', [
//    'deploy:tag-deployment', //ONLY USE THIS TASK IF NOT DEPLOYING USING THE PARALLEL FEATURE
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'deploy:symlink:web',
    'deploy:cp-htaccess',
    'cleanup',
])->desc('Deploy your project');

after('deploy-test', 'success');