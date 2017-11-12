<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iLink;
use mhndev\hal\Contract\iPaginated;
use Psr\Http\Message\RequestInterface;

/**
 * Class Paginated
 * @package mhndev\hal
 */
class Paginated extends Resource implements iPaginated
{

    /**
     * @var integer
     */
    protected $count;

    /**
     * @var integer
     */
    protected $total;

    /**
     * @var integer
     */
    protected $current_page;

    /**
     * @var iLink
     */
    protected $first;

    /**
     * @var iLink
     */
    protected $prev;

    /**
     * @var iLink
     */
    protected $self;

    /**
     * @var iLink
     */
    protected $next;

    /**
     * @var iLink
     */
    protected $last;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $pageName;

    /**
     * @var string
     */
    protected $totalName;

    /**
     * @var string
     */
    protected $countName;

    /**
     * Paginated constructor.
     *
     * @param iterable $properties
     * @param int $count
     * @param int $total
     * @param RequestInterface $request
     * @param string $pageName
     * @param string $countName
     * @param string $totalName
     */
    function __construct(
        $properties,
        integer $count,
        integer $total,
        RequestInterface $request,
        $pageName = 'page',
        $countName = 'count',
        $totalName = 'total'
    )
    {
        parent::__construct($properties);

        $this->count = $count;
        $this->total = $total;
        $this->request = $request;
        $this->pageName = $pageName;
        $this->countName = $countName;
        $this->totalName = $totalName;

        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if(array_key_exists($this->pageName, $parsed_query)){
            $this->current_page = $parsed_query[$this->pageName];
        }else{
            $this->current_page = 1;
        }

    }


    /**
     * @return $this
     */
    function addLinks()
    {
        $this->addLink($this->genFirstLink());
        $this->addLink($this->genPrevLink());
        $this->addLink($this->genSelfLink());
        $this->addLink($this->genNextLink());
        $this->addLink($this->genLastLink());

        return $this;
    }



    /**
     * @return Link
     */
    private function genFirstLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);
        $parsed_query[$this->pageName] = 1;
        $query_string = http_build_query($parsed_query);
        return new Link('first', $path.$query_string);
    }


    /**
     * @return Link
     */
    private function genPrevLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if($this->current_page == 1){
            return null;
        }
        else{
            $parsed_query[$this->pageName] = $this->current_page - 1;

            $query_string = http_build_query($parsed_query);

            return new Link('prev', $path.$query_string);
        }
    }

    /**
     * @return Link
     */
    private function genSelfLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if(empty($parsed_query[$this->pageName])){
            $parsed_query[$this->pageName] = 1;
        }

        $query_string = http_build_query($parsed_query);

        return new Link('self', $path.$query_string);
    }

    /**
     * @return Link
     */
    private function genNextLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if(empty($parsed_query[$this->pageName])){
            $parsed_query[$this->pageName] = 2;
        }else{
            $parsed_query[$this->pageName] = $this->current_page + 1;
        }

        $query_string = http_build_query($parsed_query);

        return new Link('next', $path.$query_string);
    }


    /**
     * @return Link
     */
    private function genLastLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        $total = $this->getTotal();
        $count = $this->getCount();

        $page = $total / $count;
        if($total % $count !== 0){
            $page = 1;
        }

        $parsed_query[$this->pageName] = $page;

        $query_string = http_build_query($parsed_query);
        return new Link('last', $path.$query_string);
    }

    /**
     * @return integer
     */
    function getCount()
    {
        return $this->count;
    }

    /**
     * @return integer
     */
    function getTotal()
    {
        return $this->total;
    }


    /**
     * @return iLink
     */
    function getFirstLink()
    {
        return $this->first ? $this->first : $this->genFirstLink();
    }

    /**
     * @return iLink
     */
    function getSelfLink()
    {
        return $this->self ? $this->self : $this->genSelfLink();
    }

    /**
     * @return iLink
     */
    function getPrevLink()
    {
        return $this->prev ? $this->prev : $this->genPrevLink();
    }

    /**
     * @return iLink
     */
    function getNextLink()
    {
        return $this->next ? $this->next : $this->genNextLink();
    }

    /**
     * @return iLink
     */
    function getLastLink()
    {
        return $this->last ? $this->last : $this->genLastLink();
    }

}
