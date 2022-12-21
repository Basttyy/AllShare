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

final class NotFoundEvent
{
    private $pool;

    public function __construct(ConnectionsPool $pool)
    {
        $this->pool = $pool;
    }

    public function __invoke (WebSocket $conn, string $message)
    {
        //TODO Log request and message
        //logger()->info($conn->request->getUri()->getScheme().'://'.$conn->request->getUri()->getHost().':'.$conn->request->getUri()->getPort().$conn->request->getUri()->getPath());
        logger()->info($message);
    }
}