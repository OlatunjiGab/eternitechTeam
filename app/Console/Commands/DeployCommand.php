<?php

namespace App\Console\Commands;

use App\Models\Merchants\Merchant;
use App\Models\Products\Product;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Classes\WriteToFile;

class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deploy latest code ';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * To be used for quick testing of miscellaneous code from the command-line.
     *
     * @return mixed
     */
    public function handle()
    {
        echo exec('composer install');
        echo exec('php artisan migrate --force');
        echo exec('php artisan optimize');
        echo exec('php artisan cache:clear');
        echo exec('php artisan config:clear');
        echo exec('php artisan route:clear');
        echo exec('php artisan view:clear');
        echo exec('php artisan storage:link');
        // echo exec('php artisan queue:restart');
    }

}
