<?php

namespace App\Controllers\TcpEvents\Terminal;

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

final class Settings
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

            if ($data["message"] =="there are no orders currently") {
                
            } else if (\strpos($data["message"], "does not exist in the list") !== false) {

            } else if (\strpos($data["message"], "unable to modify order with ticket") !== false) {

            } else if (\strpos($data["message"], "Modified a ") !== false) {

            } else {
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