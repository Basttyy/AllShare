<?php

namespace App\Controllers;

use App\Auth\JwtAuthenticator;
use App\Libraries\Helpers\JsonResponse;
use App\Exceptions\UserNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Filesystem\Factory;
use React\Filesystem\Node\FileInterface;
use React\Http\Message\Response;
use Throwable;

final class HomeController
{
    //@param \App\Auth\JwtAuthenticator
    private $authenticator;

    public function __construct(JwtAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $content = "";
        Factory::create()->detect(__DIR__.'../../../public_html/index.html')->then(function (FileInterface $file) {
            return $file->getContents();
        })->then(static function (string $contents) use (&$content) {
            $content = $contents;
        },
        function (Throwable $throwable) {
            echo $throwable;
        });
        
        return Response::html($content)->withStatus(Response::STATUS_OK);
    }

    private function extractEmail(ServerRequestInterface $request): ?string
    {
        $params = json_decode((string)$request->getBody(), true);

        return $params['email'] ?? '';
    }
}