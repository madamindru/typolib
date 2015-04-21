<?php

//use Bit3\GitPhp\GitConfig;
use Bit3\GitPhp\GitException;
use Bit3\GitPhp\GitRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function insertRules($rules, $file_name)
{
    $logger = new Logger('Insert');
    $logger->pushHandler(new StreamHandler(INSTALL_ROOT
                                          . 'logs/insert-errors.log'));
    $repo = 'typolib';
    $repo_url = 'https://github.com/' . urlencode(TYPOLIB_GITHUB_ACCOUNT)
              . '/' . $repo . '.git';
    $directory = DATA_ROOT . $repo . '/';
    $file_name = $directory . 'data/' . $file_name . '.php';
    $config_file = $directory . '.git/config';
    $user_config = "[user]\n"
                 . "	email = al2c-typolib@googlegroups.com\n"
                 . "	name = Typolib\n";
    $typolib_remote = 'origin';
    $client_remote = 'github';
    $client_remote_url = 'https://' . urlencode(CLIENT_GITHUB_ACCOUNT) . ':'
                       . urlencode(CLIENT_GITHUB_PASSWORD) . '@github.com/'
                       . urlencode(CLIENT_GITHUB_ACCOUNT) . '/' . $repo
                       . '.git';
    $branch = 'mabranche3';

    try {
        $git = new GitRepository($directory);

        // Clone a fresh repo if it's folder is empty
        if (! is_dir($directory)) {
            $git->cloneRepository()->execute($repo_url);
            $git->remote()->add($client_remote, $client_remote_url)->execute();
            $git->fetch()->execute($client_remote);
            file_put_contents($config_file, $user_config, FILE_APPEND);
        }

        // Add client remote if it's missing
        if (! in_array($client_remote, $git->remote()->getNames())) {
            $git->remote()->add($client_remote, $client_remote_url)->execute();
            $git->fetch()->execute($client_remote);
        }

        // Fetch latest changes to master branch, then switch to master
        $git->fetch()->execute($typolib_remote, 'master');
        $git->checkout()->execute($typolib_remote . '/master');

        // Remove branches both remotely and locally
        if (in_array($client_remote . '/' . $branch, $git->branch()->remotes()->getNames())) {
            $git->push()->execute($client_remote, ':' . $branch);
        }
        if (in_array($branch, $git->branch()->getNames())) {
            $git->branch()->delete()->execute($branch);
        }

        // Create a fresh branch
        $git->branch()->execute($branch);
        $git->checkout()->execute($branch);

        // Update content in repository
        if (! file_put_contents($file_name, $rules)) {
            $logger->error('Can\'t write into data folder');
        }

        // Add files to git index, commit and push to client remote
        $git->add()->execute($file_name);
        $git->commit()->message('Commit message')->execute();
        $git->push()->execute($client_remote, $branch);
    } catch (GitException $e) {
        $logger->error("Failed committing changes to $file_name. Error: "
                       . $e->getMessage());
    }

    return 0;
}

insertRules("Règle 1\nRègle 2\n", 'test');
