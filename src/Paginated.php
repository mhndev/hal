<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iLink;
use mhndev\hal\Contract\iPaginated;

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
     * Paginated constructor.
     * @param iterable $properties
     * @param int $count
     * @param int $total
     */
    function __construct($properties, integer $count, integer $total)
    {
        parent::__construct($properties);

        $this->count = $count;
        $this->total = $total;
    }


    /**
     * @param string $base_uri
     * @return $this
     */
    function addLinks(string $base_uri)
    {
        $this->addLink(new Link('first', $base_uri));
        $this->addLink(new Link('self' , $base_uri));
        $this->addLink(new Link('prev' , $base_uri));
        $this->addLink(new Link('next' , $base_uri));
        $this->addLink(new Link('last' , $base_uri));

        return $this;
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
        return $this->first;
    }

    /**
     * @return iLink
     */
    function getSelfLink()
    {
        return $this->self;
    }

    /**
     * @return iLink
     */
    function getPrevLink()
    {
        return $this->prev;
    }

    /**
     * @return iLink
     */
    function getNextLink()
    {
        return $this->next;
    }

    /**
     * @return iLink
     */
    function getLastLink()
    {
        return $this->last;
    }

}
