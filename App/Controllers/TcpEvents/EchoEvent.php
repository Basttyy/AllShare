<?php

namespace App\Controllers\TcpEvents;

use App\Exceptions\UserNotFoundException;
use App\Libraries\Helpers\ConnectionsPool;
use App\Libraries\Helpers\JsonResponse;
use App\Libraries\Helpers\Validator;
use App\Models\Order;
use Exception;
use Ratchet\Client\WebSocket;

use function React\Async\await;
use function React\Promise\Timer\sleep;

final class EchoEvent
{
    private $pool;
    public function __construct(ConnectionsPool $pool)
    {
        $this->pool = $pool;
    }

    public function __invoke (WebSocket $conn, string $message)
    {
        //$this->pool->sendAll($message);

        echo "chat message content is $message".PHP_EOL;
    }
}