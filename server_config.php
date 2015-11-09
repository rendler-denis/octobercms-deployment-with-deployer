<?php
/**
 * Author : Denis-Florin Rendler
 * Date   : 02/11/2015
 * Project: octo
 *
 * Copyright (c) 2015 Denis-Florin Rendler <connect@rendler.me>
 */

server('octo-deploy', 'YOUR-SERVER-IP-OR-DOMAIN', '22')
    ->user('vagrant')
    ->identityFile('ssh1/id_rsa.pub', 'ssh1/id_rsa', 'YOUR_SSH_KEY_PASSWORD')
    ->env('deploy_path', '/var/www/octo.dep')
    ->env('branch', 'master')
    ->stage('vtest');

server('octo-deploy2', 'YOUR-SECOND-SERVER-IP-OR-DOMAIN', '22')
    ->user('vagrant')
    ->identityFile('ssh2/id_rsa.pub', 'ssh2/id_rsa', 'YOUR_SSH_KEY_PASSWORD')
    ->env('deploy_path', '/var/deploy_test/www/octo.dep')
    ->env('branch', 'master')
    ->stage('vtest');