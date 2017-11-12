<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iLink;
use mhndev\hal\Contract\iResource;

/**
 * Class Resource
 * @package mhndev\hal
 */
class Resource implements iResource
{

    /**
     * @var iterable
     */
    protected $properties;

    /**
     * @var iterable
     */
    protected $links;

    /**
     * @var iterable
     */
    protected $embedded;


    /**
     * Resource constructor.
     * @param iterable $properties
     */
    function __construct(iterable $properties)
    {
        $this->properties = $properties;
    }



    /**
     * get plain data
     *
     * @return iterable
     */
    function getProperties()
    {
        return $this->properties;
    }

    /**
     * collection of iLink @see iLink
     *
     * @return iterable
     */
    function getLinks()
    {
        return $this->links;
    }

    /**
     * collection of iResource @see iResource
     *
     * @return iterable
     */
    function getEmbedded()
    {
        return $this->embedded;
    }

    /**
     * @param iLink $link
     * @return $this
     */
    function addLink(iLink $link)
    {
        $this->links[$link->getRelation()] = $link;

        return $this;
    }

    /**
     * @param iLink $link
     * @return $this
     */
    function removeLink(iLink $link)
    {
        unset($this->links[$link->getRelation()]);

        return $this;
    }

    /**
     * @param iResource $resource
     * @param string $index
     * @return $this
     */
    function addEmbeddedResource(iResource $resource, string $index)
    {
        $this->embedded[$index] = $resource;

        return $this;
    }

    /**
     * @param string $index
     *
     * @return $this
     */
    function removeEmbeddedByIndex(string $index)
    {
        unset($this->embedded[$index]);

        return $this;
    }


    /**
     * @return boolean
     */
    function hasLink()
    {
        return !empty($this->links);
    }

    /**
     * @return boolean
     */
    function hasEmbedded()
    {
        return !empty($this->embedded);
    }


}
