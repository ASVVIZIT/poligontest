<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartSerializingStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statist:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command s Запуск задания сериализации статистики';

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
        \App\Jobs\SendMessage::withChain([
            new \Rinvex\Statistics\Jobs\CrunchStatistics(),
        ])->dispatch("Старт задания...")->delay(now()->addMinute(1));
    }
}
