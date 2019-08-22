<?php

namespace App\Controllers;

use App\Proxies;
use App\Exceptions;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;

use GuzzleHttp\Exception\BadResponseException;

/**
 * Serves all `api`-scoped endpoints.
 * 
 * This controller uses different logic than common controllers,
 * because we should response with json, not with Phalcon Response.
 */
class ApiController extends Controller
{
    /**
     * Handler for `signin` request.
     * 
     * Uses proxy for determine should we sign user in or not.
     * 
     * @return Phalcon\Http\Response
     */
    public function signin()
    {
        $UsersProxy = new Proxies\UsersProxy($this->config);

        $credentials = $this->getCredentialsFrom($this->request);
       
        // First of all, try to check, exists user or not
        try {
            if($UsersProxy->isUserExistsBy($credentials)) {
                return $this->makeResponse(200, 'Successful Authorization');
            }
        } catch (BadResponseException $Exception) {
            // We forced to catch these Exceptions because Guzzle throwns it
            // if response has code not 200 OK
            $Response = $Exception->getResponse();

            $decodedResponse = json_decode(
                $Response->getBody()->getContents()
            );
            
            throw new Exceptions\ResponseException($decodedResponse->result);
        } catch(\Exception $Exception) {
            return $this->makeResponse(500, $Exception->getMessage());
        }

        return $this->makeResponse(500, 'Internal Server Error');
    }

    /**
     * Returns array with credentials from request.
     * 
     * @param Request $Request 
     * @return arrray
     */
    private function getCredentialsFrom(Request $Request): array
    {
        return [
            'login' => $Request->getJsonRawBody()->login ?? null,
            'password' => $Request->getJsonRawBody()->password ?? null,
        ];
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