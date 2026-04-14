<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\service\SearchService;

class IndexCleanup extends Command
{
    protected function configure()
    {
        $this->setName('index:cleanup')
            ->setDescription('Clean up orphaned and stale search index entries');
    }

    protected function execute(Input $input, Output $output)
    {
        $searchService = new SearchService();
        $count = $searchService->cleanup();
        $output->writeln("Removed {$count} orphaned search index entries.");
    }
}
