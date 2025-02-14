<?php

declare(strict_types=1);

namespace App\Job;

use App\Controller\OrderController;
use Hyperf\AsyncQueue\Job;

class OrderCreateJob extends Job
{
    public $params;

    public function __construct($params)
    {
        // 这里最好是普通数据，不要使用携带 IO 的对象，比如 PDO 对象
        $this->params = $params;
    }

    public function handle()
    {
        OrderController::createRun($this->params);
    }
}