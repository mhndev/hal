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

            if($accept == '*/*'){
                $accept = 'application/json';
            }

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


    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @param $result
     * @return HalResponse|ResponseInterface
     */
    static function paginatedListFromRepository(
        RequestInterface $request,
        ResponseInterface $response,
        $result
    )
    {
        $query_string = $request->getUri()->getQuery();
        parse_str($query_string, $query_array);

        $query = $result['query'];
        unset($result['query']);

        $keys = array_keys($result);

        $types = implode(',', $keys);

        $resource = new Resource([
            'search' => $types,
            'keyword' => $query
        ]);

        foreach ($result as $type => $item){

            $data = $item['data'];

            if(is_object($data) && method_exists($data, 'toArray') ){
                $data = $item['data']->toArray();
            }

            $embedded = new Paginated($data, $item['total'], $request);

            $resource->addEmbeddedResource($embedded, $type);
        }

        return self::make($request, $response, $resource);
    }





}
