<?php

namespace Merkeleon\Nsq;


use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Queue\QueueServiceProvider;
use Merkeleon\Nsq\Providers\WorkCommandProvider;
use Merkeleon\Nsq\Queue\Connector as NsqConnector;
use Merkeleon\Nsq\Queue\Worker;

class NsqServiceProvider extends QueueServiceProvider
{
    /**
     * Register the connectors on the queue manager.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);

        $this->registerNsqConnector($manager);
    }

    /**
     * Register the connectors on the queue manager.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    public function registerNsqConnector($manager)
    {
        $key = 'queue.connections.nsq';
        $this->app['config']->set($key, require __DIR__ . '/../config/queue.php');

        $manager->addConnector('nsq', function () {
            return new NsqConnector();
        });

        // add defer provider, rebind work command
        $this->app->addDeferredServices([WorkCommandProvider::class]);
    }

    /**
     * Register the queue worker.
     *
     * @return void
     */
    protected function registerWorker()
    {
        $this->app->singleton('queue.worker', function () {
            $isDownForMaintenance = function () {
                return $this->app->isDownForMaintenance();
            };

            return new Worker(
                $this->app['queue'],
                $this->app['events'],
                $this->app[ExceptionHandler::class],
                $isDownForMaintenance
            );
        });
    }
}
