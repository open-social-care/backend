<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->executeClearCache();
        $this->executeClearConfigurations();
        $this->executeCacheConfigurations();
        $this->executeClearRoutes();
        $this->executeCacheRoutes();
        $this->executeClearEvent();
        $this->executeCacheEvents();
    }

    private function executeClearCache()
    {
        $clearCacheMessages = ['success' => 'Cache da aplicação foi apagado.'];
        $this->executeWithMessages('cache:clear', [], $clearCacheMessages);
    }

    private function executeClearConfigurations()
    {
        $clearCacheConfigurationMessages = ['success' => 'Cache das configurações foi apagado.'];
        $this->executeWithMessages('config:clear', [], $clearCacheConfigurationMessages);
    }

    private function executeCacheConfigurations()
    {
        $clearCacheConfigurationMessages = ['success' => 'Cache das configurações foi criado.'];
        $this->executeWithMessages('config:cache', [], $clearCacheConfigurationMessages);
    }

    private function executeClearRoutes()
    {
        $clearCacheRouteMessages = ['success' => 'Cache das rotas foi apagado.'];
        $this->executeWithMessages('route:clear', [], $clearCacheRouteMessages);
    }

    private function executeCacheRoutes()
    {
        $cacheRouteMessages = [
            'success' => 'Cache das rotas foi criado.',
            'failed' => 'Erro ao criar cache das rotas.',
        ];
        $this->executeWithMessages('route:cache', [], $cacheRouteMessages);
    }

    private function executeClearEvent()
    {
        $clearCacheEventMessages = ['success' => 'Cache dos eventos foi apagado.'];
        $this->executeWithMessages('event:clear', [], $clearCacheEventMessages);
    }

    private function executeCacheEvents()
    {
        $clearCacheEventMessages = ['success' => 'Cache dos eventos foi criado.'];
        $this->executeWithMessages('event:cache', [], $clearCacheEventMessages);
    }

    private function executeWithMessages($call, $options, $messages = [])
    {
        try {
            $this->callSilent($call, $options);

            if (isset($messages['success'])) {
                $this->info($messages['success']);
            }
        } catch (\Exception $e) {
            if (isset($messages['failed'])) {
                $this->error($messages['failed']);
            }
        }
    }
}
