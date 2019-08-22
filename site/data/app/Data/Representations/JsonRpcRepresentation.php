<?php

namespace App\Data\Representations;

use App\Contracts;

/**
 * Provides interface to create and retrieve JsonRpc formatted
 * arrays from input data; and vice verca, original data from
 * JsonRpc formatted arrays.
 * 
 * On input you can provide JsonRpc or original, not formatted array of data.
 * In both of cases you will be able to get JsonRpc or non-formatted data.
 */
class JsonRpcRepresentation implements Contracts\DataRepresentation
{
    private $data;
    private $method;

    private $isJsonRpc = false;

    private const PROTOCOL_VERSION = '2.0';

    public function __construct(array $data, ?string $method = null)
    {
        $this->data = $data;
        $this->method = $method;

        if($this->isDataJsonRpcFormatted()) {
            $this->isJsonRpc = true;
        }
    }

    /**
     * Returns JsonRpc formatted array.
     * As ID used md5 hash of serialazed data and method
     * that combined by dot.
     * 
     * @return array
     */
    public function getRepresentation()
    {
        if($this->isJsonRpc) {
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

    /**
     * Returns original, non-formatted data.
     * If instance was created with formatted data,
     * `params` field of JsonRpc array will be returned.
     * Otherwise returns provided data.
     * 
     * @return array
     */
    public function getOriginal()
    {
        if($this->isJsonRpc) {
            return $this->data['params'];
        }

        return $this->data;
    }

    /**
     * Checks if current `$data` is JsonRpc formatted array.
     * 
     * @return bool
     */
    private function isDataJsonRpcFormatted(): bool
    {
        return (
            isset($this->data['params'])
            &&
            isset($this-data['id'])
            &&
            isset($this->data['method'])
        );
    }
}