<?php

use Bit3\GitPhp\GitException;
use Bit3\GitPhp\GitRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function insertRules($rules, $file_name)
{
    $logger = new Logger('Insert');
    $logger->pushHandler(new StreamHandler(INSTALL_ROOT
        . 'logs/insert-errors.log'));
    try {
        $directory = INSTALL_ROOT . 'data/typolib/';
        $git = new GitRepository($directory);
        if (! is_dir($directory)) {
            $git->cloneRepository()->execute('https://github.com/'
                . TYPOLIB_GITHUB_ACCOUNT . '/typolib.git');
        }

        if (! in_array('github', $git->remote()->getNames())) {
            $git->remote()
            ->add('github', 'https://' . CLIENT_GITHUB_ACCOUNT . ':'
                . CLIENT_GITHUB_PASSWORD . '@github.com/typolib/typolib.git')
            ->execute();
            $git->fetch()->execute('github');
        }
        $git->fetch()->execute('origin', 'master');
        $git->checkout()->execute('origin/master');

        if (in_array('github/mabranche2', $git->branch()->remotes()->getNames())) {
            $git->push()->execute('github', ':mabranche2');
            $git->fetch()->execute('github');
        }

        if (in_array('mabranche2', $git->branch()->getNames())) {
            $git->branch()->delete()->execute('mabranche2');
        }

        $git->branch()->execute('mabranche2');
        $git->checkout()->execute('mabranche2');

        $file_name = DATA_ROOT . '/typolib/data/' . $file_name . '.php';
        if (! file_put_contents($file_name, $rules)) {
            $logger->error("Can't write into data folder");
        }

        $git->add()->execute($file_name);
        $git->commit()->message('Commit message')->execute();
        $git->push()->execute('github', 'mabranche2');
    } catch (GitException $e) {
        $logger->error("Failed committing changes to " . $file_name . ". Error: "
            . $e->getMessage());
    }
}

insertRules("Règle 1\nRègle 2\n", "test");
