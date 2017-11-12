<?php
namespace mhndev\hal\Contract;

/**
 * Interface iHalResource
 * @package mhndev\hal
 */
interface iResource
{


    /**
     * get plain data
     *
     * @return iterable
     */
    function getProperties();

    /**
     * @return boolean
     */
    function hasLink();


    /**
     * collection of iLink @see iLink
     *
     * @return iterable
     */
    function getLinks();


    /**
     * @param iLink $link
     * @return $this
     */
    function addLink(iLink $link);

    /**
     * @param iLink $link
     * @return $this
     */
    function removeLink(iLink $link);

    /**
     * @return boolean
     */
    function hasEmbedded();

    /**
     * collection of iResource @see iResource
     *
     * @return iterable
     */
    function getEmbedded();

    /**
     * @param iResource $resource
     * @param string $index
     * @return $this
     */
    function addEmbeddedResource(iResource $resource, string $index);

    /**
     * @param string $index
     *
     * @return $this
     */
    function removeEmbeddedByIndex(string $index);

}
