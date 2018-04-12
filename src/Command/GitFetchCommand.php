<?php

namespace Wispiring\CodeSpace\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Wispiring\CodeSpace\Scanner;
use Wispiring\CodeSpace\Exporter\AtomProjectsCsonExporter;
use RuntimeException;

class GitFetchCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('git:fetch')
            ->setDescription('Scan code repositories and fetch from origin')
            ->addOption(
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The path to scan'
            )
            ->addOption(
                'pull',
                null,
                InputOption::VALUE_NONE,
                'Direct pull if the repo is on master'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pullIfMaster = $input->getOption('pull');

        $spaces = $this->scanPath($input);

        $o = $this->getStyler($input, $output);
        $o->title('CodeSpace: Updating code repositories with Git');
        $o->section("\xF0\x9F\x93\x81 : [<info>".$this->scanPath."</>]");

        $o->progressStart(count($spaces));
        foreach ($spaces as $project) {
            $o->newLine();
            $o->text('Starting '.$project->getPath().' ...');
            $done = false;
            if ($pullIfMaster) {
                if (trim(shell_exec('cd '.$project->getPath().' && git rev-parse --abbrev-ref HEAD')) == 'master') {
                    $o->text('<fg=cyan>Directly pulling to master ... '.shell_exec('cd '.$project->getPath().' && git pull').'</> <info>Done!</>');
                    $done = true;
                }
            }

            if (!$done) {
                $o->text('<comment>Fetching origin ... '.shell_exec('cd '.$project->getPath().' && git fetch origin').'</> <info>Done!</>');
            }
            $o->progressAdvance();
            // $o->newLine();
        }
        $o->progressFinish();
        $o->success("\xF0\x9F\x93\xA3  ".count($spaces)." code repositories fetched. \xF0\x9F\x8E\x89");
    }
}
