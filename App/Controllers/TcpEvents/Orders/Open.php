<?php

namespace App\Controllers\TcpEvents\Orders;

use App\Exceptions\UserNotFoundException;
use App\Libraries\Helpers\Arr;
use App\Libraries\Helpers\ConnectionsPool;
use App\Libraries\Helpers\JsonResponse;
use App\Libraries\Helpers\Validator;
use App\Models\Order;
use Exception;
use Ratchet\Client\WebSocket;

use function React\Async\await;
use function React\Promise\Timer\sleep;

final class Open
{
    private $pool;
    public function __construct(ConnectionsPool $pool)
    {
        $this->pool = $pool;
    }

    public function __invoke (WebSocket $conn, string $data)
    {
        try {
            $data = json_decode(base64_decode($data), true);

            switch ($data["message"]) {
                case "sync copytrades":
                    $this->syncCopyTrade($data);
                    break;
                case "unable to open order":
                    $this;
                    break;
                case "order opened but couldn't get order info":
                    $this;
                    break;
                case "order opened with info":
                    $this;
                    break;
                default:
                    logger()->info("unknown event on orders/open", (array)$data);
            }
            $separator = $this->pool->remote_seperator;

            $data = Arr::except($data, "clients");
            $message = base64_encode(\json_encode($data));
            
            //$conn->send("events/order/open{$separator}{$message}");
        } catch (Exception $ex) {
            logger()->error($ex->getMessage(), $ex->getTrace());
        }
    }

    private function syncCopyTrade (array $data)
    {
        $retry = [];
        foreach ($data["clients"] as $client) {
            $this->pool->send($data["command"], $client)->then(
                function () {

                },
                function (string|Exception $err) use ($client, &$retry){
                    if ($err instanceof Exception) {
                        logger()->error($err->getMessage(), $err->getTrace());
                    } else {
                        $retry[] = $client;
                        /// TODO: find a way to retry sending copytrade data to clients when failed
                    }
                }
            );
        }
    }

    private function failedOpenOrder (array $data)
    {

    }

    private function orderOpenedInfo (array $data)
    {

    }

    private function orderOpened (array $data)
    {

    }
}