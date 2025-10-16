<?php

namespace App\Console\Commands;

use App\Temporal\Activities\UserActivitiesImpl;
use App\Temporal\Workflows\UserWorkflowImpl;
use Illuminate\Console\Command;
use Temporal\WorkerFactory;

class TemporalWorker extends Command
{
    protected $signature = 'temporal:worker';

    protected $description = 'Run Temporal Worker';

    public function handle()
    {
//        $this->info('Starting Temporal Worker...');
        $factory = WorkerFactory::create();
        $worker = $factory->newWorker('user-task-queue');
        $worker->registerWorkflowTypes(UserWorkflowImpl::class);
        $worker->registerActivity(UserActivitiesImpl::class);

//        $this->info('Worker started on task queue: user-task-queue');
//        $this->info('Listening for workflows...');

        $factory->run();
    }
}
