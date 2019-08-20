<?php

namespace App\Controllers;

use App\Proxies;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Http\Request;

use GuzzleHttp;

class ApiController extends Controller
{
    public function signin()
    {
        $UsersProxy = new Proxies\UsersProxy($this->config);

        $credentials = $this->getCredentialsFrom($this->request);
       
        try {
            if(UsersProxy->isUserExistsBy($credentials)) {
                return $this->makeResponse(200, 'Successful Authorization');
            }
        } catch (GuzzleHttp\Exception\BadResponseException $Exception) {
            // We forced to catch these Exceptions because Guzzle throwns it
            // if response has code not 200 OK
            $Response = $Exception->getResponse();

            $decodedResponse = json_decode(
                $Response->getBody()->getContents()
            );
            
            return $this->makeResponse(500, $decodedResponse->result);
        } catch(\Exception $Exception) {
            return $this->makeResponse(500, $Exception->getMessage());
        }

        return $this->makeResponse(500, 'Internal Server Error');
    }

    private function getCredentialsFrom(Request $Request)
    {
        return [
            'login' => $Request->getJsonRawBody()->login,
            'password' => $Request->getJsonRawBody()->password,
        ];
    }

    private function makeResponse(int $code, string $message)
    {
        $Response = new Response;

        $Response->setStatusCode($code);
        $Response->setContent(json_encode([
            'result' => $message,
        ]));

        return $Response;
    }
}