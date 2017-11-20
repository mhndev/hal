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
     * @var RequestInterface
     */
    protected static $request;


    /**
     * @var ResponseInterface
     */
    protected static $response;

    /**
     * @var iResource
     */
    protected static $resource;


    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    static function set(
        RequestInterface $request,
        ResponseInterface $response
    )
    {
        self::$request = $request;
        self::$response = $response;
    }


    /**
     * @param iResource $resource
     *
     * @return ResponseInterface|static
     *
     * @throws \Exception
     */
    static function make(iResource $resource)
    {
        if(self::$request->hasHeader('ACCEPT')){
            $accept = self::$request->getHeader('ACCEPT')[0];

            if($accept == '*/*'){
                $accept = 'application/json';
            }

        }else{
            $accept = 'application/json';
        }


        if($accept == 'application/json'){
            $response = self::$response->withHeader('Content-type', 'application/json');
            $response->getBody()->write((new Presenter($resource))->asJson());
        }

        elseif ($accept == 'application/xml'){
            $response = self::$response->withHeader('Content-type', 'application/xml');
            $response->getBody()->write((new Presenter($resource))->asXml());
        }

        else{
            throw new \Exception('unsupported accept type');
        }

        return $response;
    }


    /**
     * @return int
     */
    static function offset()
    {
        $query_string = self::$request->getUri()->getQuery();
        parse_str($query_string, $query_array);
        $page = array_key_exists('page', $query_array) ? $query_array['page'] : 1;
        $offset = ($page - 1) * self::limit();

        return $offset;
    }


    /**
     * @return int
     */
    static function limit()
    {
        $query_string = self::$request->getUri()->getQuery();
        parse_str($query_string, $query_array);
        $per_page = array_key_exists('per_page', $query_array) ?
            $query_array['per_page'] :
            10
        ;

        return $per_page;
    }


    /**
     * @param $result
     * @return HalResponse|ResponseInterface
     */
    static function paginatedListFromRepository($result)
    {
        $query_string = self::$request->getUri()->getQuery();
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

            $embedded = new Paginated($data, $item['total'], self::$request);

            $resource->addEmbeddedResource($embedded, $type);
        }

        return self::make($resource);
    }





}
