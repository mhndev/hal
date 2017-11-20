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


    const perPage = 10;

    const page = 1;


    /**
     * @var integer
     */
    protected $count;


    /**
     * @var int
     */
    protected $page_count;


    /**
     * @var integer
     */
    protected $total;

    /**
     * @var integer
     */
    protected $current_page;

    /**
     * @var int
     */
    protected $last_page;

    /**
     * @var int
     */
    protected $first_page;

    /**
     * @var int
     */
    protected $next_page;

    /**
     * @var int
     */
    protected $prev_page;

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
    protected $page_name;

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
     * @param int $total
     * @param string $name
     * @param RequestInterface $request
     * @param string $page_name
     * @param string $count_name
     * @param string $total_name
     */
    function __construct(
        $properties,
        int $total,
        RequestInterface $request,
        string $name = null,
        $page_name = 'page',
        $count_name = 'count',
        $total_name = 'total'
    )
    {
        parent::__construct($properties);


        $this->total = $total;
        $this->request = $request;
        $this->countName = $count_name;
        $this->totalName = $total_name;

        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if($name){
            if(array_key_exists('perPage_'.$name, $parsed_query) &&
                !empty($parsed_query['perPage_'.$name])
            ){
                $this->count = min($this->total, $parsed_query['perPage_'.$name]);
            }
            else{
                $this->count = min(self::perPage, $this->total);
            }
            $this->page_name = 'page_'.$name;
        }//
        else{
            $count = empty($parsed_query['perPage']) ? self::perPage : $parsed_query['perPage'];

            $this->count = min($count, $this->total);

            $this->page_name = 'page';
        }

        $this->addLinks();
    }


    /**
     * @return $this
     */
    function addLinks()
    {
        $this->addLink($this->getFirstLink());
        $this->addLink($this->getPrevLink());
        $this->addLink($this->getSelfLink());
        $this->addLink($this->getNextLink());
        $this->addLink($this->getLastLink());

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

        $parsed_query[$this->getPageName()] = $this->getFirstPage();

        $query_string = http_build_query($parsed_query);

        return new Link('first', $path.'?'.$query_string);
    }


    /**
     * @return Link
     */
    private function genPrevLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        $parsed_query[$this->getPageName()] = $this->getPrevPage();

        $query_string = http_build_query($parsed_query);
        return new Link('prev', $path.'?'.$query_string);
    }

    /**
     * @return Link
     */
    private function genSelfLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        $parsed_query[$this->getPageName()] = $this->getCurrentPage();

        $query_string = http_build_query($parsed_query);
        return new Link('self', $path.'?'.$query_string);
    }

    /**
     * @return Link
     */
    private function genNextLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        $parsed_query[$this->getPageName()] = $this->getNextPage();


        $query_string = http_build_query($parsed_query);

        return new Link('next', $path.'?'.$query_string);
    }


    /**
     * @return Link
     */
    private function genLastLink()
    {
        $path = $this->request->getUri()->getPath();
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        $parsed_query[$this->getPageName()] = $this->getLastPage();

        $query_string = http_build_query($parsed_query);
        return new Link('last', $path.'?'.$query_string);
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

    /**
     * @return string
     */
    public function getPageName(): string
    {
        return $this->page_name;
    }


    /**
     * @return int
     */
    private function getFirstPage()
    {
        if($this->first_page){
            return $this->first_page;
        }

        return $this->setFirstPage()->getFirstPage();
    }


    /**
     * @return $this
     */
    private function setFirstPage()
    {
        $first_page = -1;

        if($this->getTotal() > 0){
            $first_page = 1;
        }

        $this->first_page = $first_page;

        return $this;
    }


    /**
     * @return int
     */
    private function getPrevPage()
    {
        if($this->prev_page){
            return $this->prev_page;
        }

        return $this->setPrevPage()->getPrevPage();
    }

    /**
     * @return $this
     */
    function setPrevPage()
    {
        $prev_page = -1;

        if($this->getCurrentPage() > 1){
            $prev_page = $this->getCurrentPage() - 1;
        }

        $this->prev_page = $prev_page;

        return $this;
    }


    /**
     * @return int
     */
    private function getCurrentPage()
    {
        if($this->current_page){
            return $this->current_page;
        }

        return $this->setCurrentPage()->getCurrentPage();
    }


    /**
     * @return $this
     */
    private function setCurrentPage()
    {
        $query = $this->request->getUri()->getQuery();
        parse_str($query, $parsed_query);

        if(array_key_exists($this->getPageName(), $parsed_query) ){
            $current_page = $parsed_query[$this->getPageName()];
        }else{
            $current_page = 1;
        }

        $this->current_page = $current_page;

        return $this;
    }


    /**
     * @return int
     */
    private function getNextPage()
    {
        if($this->next_page){
            return $this->next_page;
        }

        return $this->setNextPage()->getNextPage();
    }


    /**
     * @return $this
     */
    function setNextPage()
    {
        $next_page = -1;

        if($this->getCurrentPage() < $this->getLastPage()){
            $next_page = $this->getCurrentPage() + 1;
        }

        $this->next_page = $next_page;

        return $this;
    }


    /**
     * @return int
     */
    private function getLastPage()
    {
        if($this->last_page){
            return $this->last_page;
        }

        return $this->setLastPage()->getlastPage();
    }


    /**
     * @return $this
     */
    private function setLastPage()
    {
        $this->last_page = $this->getPageCount();

        return $this;
    }


    /**
     * @return int
     */
    private function getPageCount()
    {
        if($this->page_count){
            return $this->page_count;
        }

        return $this->setPageCount()->getPageCount();
    }


    /**
     * @return $this
     * @throws \Exception
     */
    private function setPageCount()
    {
        $number = (int) ( $this->getTotal() / $this->getCount() );

        $remainder = fmod( $this->getTotal(), $this->getCount() );

        if( $remainder == 0 ){
            $this->page_count = $number;
        }
        else{
            $this->page_count = $number + 1;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTotalName(): string
    {
        return $this->totalName;
    }

    /**
     * @return string
     */
    public function getCountName(): string
    {
        return $this->countName;
    }

}
