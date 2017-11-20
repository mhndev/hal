<?php
namespace mhndev\hal\Contract;

/**
 * Interface iPaginated
 * @package mhndev\hal\Contract
 */
interface iPaginated extends iResource
{

    /**
     * @return integer
     */
    function getCount();

    /**
     * @return integer
     */
    function getTotal();


    /**
     * @return iLink
     */
    function getFirstLink();

    /**
     * @return iLink
     */
    function getSelfLink();

    /**
     * @return iLink
     */
    function getPrevLink();

    /**
     * @return iLink
     */
    function getNextLink();

    /**
     * @return iLink
     */
    function getLastLink();
}
