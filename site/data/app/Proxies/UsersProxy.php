<?php

namespace App\Proxies;

use App\Data\Representations as DataRepresentations;
use App\Exceptions;
use App\Contracts;

use Phalcon\Config;
use Phalcon\Di;
use GuzzleHttp;

class UsersProxy implements Contracts\Proxy
{
    private $url;
    private $JsonRpcRepresentation;

    public function __construct()
    {
        $DependencyInjector = DI::getDefault();

        $this->url = $DependencyInjector->get('config')->application->aliases->users;
        $this->url .= '/' . 'api/user/exists';
    }

    public function isUserExistsBy(array $credentials)
    {
        $this->checkProvidedCredentials($credentials);

        $client = new GuzzleHttp\Client;
        
        $jsonRpcRequest = $this->getJsonRpcPresentedRequestFrom([
            'login' => $credentials['login'],
            'password' => $credentials['password'],
        ], 'is-user-exists');
        
        $response = $client->request('POST', $this->url, [
            'json' => $jsonRpcRequest,
        ]);

        if($response && $response->getStatusCode() === 200) {
            return true;
        }

        return false;
    }

    private function checkProvidedCredentials(array $credentials)
    {
        if(!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exceptions\IncorrectCredentials;
        }
    }

    private function getJsonRpcPresentedRequestFrom(array $data, string $method)
    {
        $JsonRpc = new DataRepresentations\JsonRpcRepresentation($data, $method);

        return $JsonRpc->getRepresentation();
    }
}