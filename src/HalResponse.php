<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iResource;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HalResponse
 * @package mhndev\hal
 */
class HalResponse
{


    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param iResource $resource
     *
     * @return ResponseInterface|static
     *
     * @throws \Exception
     */
    static function make(
        RequestInterface $request,
        ResponseInterface $response,
        iResource $resource
    )
    {
        if($request->hasHeader('ACCEPT')){
            $accept = $request->getHeader('ACCEPT')[0];
        }else{
            $accept = 'application/json';
        }

        if($accept == 'application/json'){
            $response = $response->withHeader('Content-type', 'application/json');
            $response->getBody()->write((new Presenter($resource))->asJson());
        }

        elseif ($accept == 'application/xml'){
            $response = $response->withHeader('Content-type', 'application/xml');
            $response->getBody()->write((new Presenter($resource))->asXml());
        }

        else{
            throw new \Exception('unsupported accept type');
        }

        return $response;
    }


}
