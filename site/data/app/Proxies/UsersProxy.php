<?php

namespace App\Proxies;

use App\Data\Representations as DataRepresentations;
use App\Exceptions;
use App\Contracts;

use Phalcon\{
    Config, Di
};

use GuzzleHttp;

/**
 * This class encapsulate requests to the Users microservice.
 */
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

    /**
     * This method calls Users microservice and asks,
     * exists user with provided creds or not.
     * 
     * Credentials should be presented as array with
     * two keys: `login` and `password`.
     * 
     * @param array $credentials 
     * @return bool
     */
    public function isUserExistsBy(array $credentials): bool
    {
        $this->checkProvidedCredentials($credentials);

        $Client = new GuzzleHttp\Client();
        
        $jsonRpcRequestData = $this->getJsonRpcPresentedRequestFrom([
            'login' => $credentials['login'],
            'password' => $credentials['password'],
        ], 'is-user-exists');
        
        $Response = $Client->request('POST', $this->url, [
            'json' => $jsonRpcRequestData,
        ]);

        if($Response && $Response->getStatusCode() === 200) {
            return true;
        }

        return false;
    }

    /**
     * Checks if provided credentials has `login` and `password keys.
     * If required keys aren't presented, throws an exception.
     * 
     * @param array $credentials 
     * @return void
     */
    private function checkProvidedCredentials(array $credentials)
    {
        if(!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exceptions\IncorrectCredentials;
        }
    }

    /**
     * Creates and returns new json-rpc formatted array
     * from provided data and method.
     * 
     * @param array $data 
     * @param string $method 
     * @return array
     */
    private function getJsonRpcPresentedRequestFrom(array $data, string $method)
    {
        $JsonRpc = new DataRepresentations\JsonRpcRepresentation($data, $method);

        return $JsonRpc->getRepresentation();
    }
}