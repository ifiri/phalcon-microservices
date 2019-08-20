<?php

namespace App\Controllers;

use App\Models;
use App\Data\Representations as DataRepresentations;
use App\Exceptions;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ApiController extends Controller
{
    public function isUserExists()
    {
        $credentials = $this->getParamsFromJsonRpcRequest(
            (array)$this->request->getJsonRawBody()
        );

        try {
            $this->checkCredentials($credentials);

            $User = Models\User::findFirstByLogin($credentials['login']);

            if($User) {
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

    private function getParamsFromJsonRpcRequest(array $data)
    {
        $JsonRpc = new DataRepresentations\JsonRpcRepresentation($data);

        return (array)$JsonRpc->getOriginal();
    }

    private function checkCredentials(array $credentials)
    {
        if(!isset($credentials['login']) || !isset($credentials['password'])) {
            throw new Exceptions\IncorrectCredentials;
        }
    }

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