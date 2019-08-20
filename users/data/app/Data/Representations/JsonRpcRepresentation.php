<?php

namespace App\Data\Representations;

use App\Contracts;

class JsonRpcRepresentation implements Contracts\DataRepresentation
{
    private $data;
    private $method;
    private $is_json_rpc = false;

    private const PROTOCOL_VERSION = '2.0';

    public function __construct(array $data, ?string $method = null)
    {
        $this->data = $data;
        $this->method = $method;

        if($this->isJsonRpc($data)) {
            $this->is_json_rpc = true;
        }
    }

    public function getRepresentation()
    {
        if($this->is_json_rpc) {
            return $this->data;
        }

        return [
            'jsonrpc' => self::PROTOCOL_VERSION, 
            'method' => $this->method,
            'params' => $this->data,
            'id' => md5(
                serialize($this->data) . '.' . $this->method
            ),
        ];
    }

    public function getOriginal()
    {
        if($this->is_json_rpc) {
            return $this->data['params'];
        }

        return $this->data;
    }

    private function isJsonRpc($data)
    {
        return isset($data['params']) && isset($data['id']) && isset($data['method']);
    }
}