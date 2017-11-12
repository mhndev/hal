<?php
namespace mhndev\hal\Contract;

/**
 * Interface iLink
 * @package mhndev\hal
 */
interface iLink
{

    /**
     * @return string
     */
    function getRelation();

    /**
     * @return string
     */
    function getHref();

    /**
     * optional parameter
     *
     * @return string | null
     */
    function getName();

    /**
     * @return boolean
     */
    function hasName();

    /**
     * @return boolean | null
     */
    function getTemplated();

    /**
     * @return boolean
     */
    function isTemplated();

}
