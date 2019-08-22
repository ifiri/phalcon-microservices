<?php

namespace App\Controllers;

use App\Models;
use App\Data\Representations as DataRepresentations;
use App\Exceptions;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ApiController extends Controller
{
    /**
     * Handler for `user/exists` request.
     * 
     * Checks database for user with provided login. If found,
     * checks correct password or not. In something wrong,
     * returns response with 500 code.
     * 
     * @return Phalcon\Http\Response
     */
    public function isUserExists()
    {
        // First of all, get original credentials from JsonRpc encoded input
        $credentials = $this->getParamsFromJsonRpcRequest(
            (array)$this->request->getJsonRawBody()
        );

        // Then, check credentials array for correctness
        try {
            $this->checkCredentials($credentials);

            // If all is ok, try to find User by login
            $User = Models\User::findFirstByLogin($credentials['login']);

            // If found...
            if($User) {
                // ... checks password and return 200 OK
                if($User->isPasswordCorrect($credentials['password'])) {
                    return $this->makeResponse(200, 'Successful authorization');
                }

                return $this->makeResponse(500, 'Incorrect login or password');
            }
        } catch(\Exception $Exception) {
            return $this->makeResponse(500, $Exception->getMessage());
        }

        return $this->makeResponse(404, 'User not exists');
    }

    /**
     * Returns original data from JsonRpc.
     * In other words, extract `params` field from input array
     * and returns.
     * 
     * @param array $data 
     * @return array
     */
    private function getParamsFromJsonRpcRequest(array $data): array
    {
        $JsonRpc = new DataRepresentations\JsonRpcRepresentation($data);

        return (array)$JsonRpc->getOriginal();
    }

    /**
     * Checks have provided array `login` and `password` keys or not.
     * If something wrong, thrown exception.
     * 
     * @param array $credentials 
     * @return void
     */
    private function checkCredentials(array $credentials)
    {
        if(!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exceptions\IncorrectCredentials;
        }
    }

    /**
     * Creates and returns new Response instance
     * with predefined headers and content.
     * 
     * @param int $code 
     * @param string $message 
     * 
     * @return Phalcon\Http\Response
     */
    private function makeResponse(int $code, string $message)
    {
        $Response = new Response;

        $Response->setStatusCode($code);
        $Response->setHeader('Content-Type', 'application/json');
        $Response->setContent(json_encode([
            'result' => $message,
        ]));

        return $Response;
    }
}