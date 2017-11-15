<?php
namespace mhndev\hal\Contract;

/**
 * Interface iPaginated
 * @package mhndev\hal\Contract
 */
interface iPaginated extends iResource
{

    /**
     * page name default to string `page`
     *
     * @return string
     */
    function getPageName();

    /**
     * per_page name default to string `count`
     * @return string
     */
    function getPerPageName();

    /**
     * total name default to string `total`

     * @return string
     */
    function getTotalName();

    /**
     * total item count
     *
     * @return int
     */
    function getTotal();

    /**
     * per page item count default to 10
     *
     * @return int
     */
    function getPerPage();

    /**
     * page count
     *
     * @return int
     */
    function getPageCount();

    /**
     * prev page index if current page  = 1 then prev page should be -1
     *
     * @return int
     */
    function getPrevPage();

    /**
     * current page index default to 1
     *
     * @return int
     */
    function getCurrentPage();

    /**
     * first page index default to 1 if list is not empty ,
     * if list is empty then it should return -1
     *
     * @return int
     */
    function getFirstPage();

    /**
     * next page index if current page index equals to last page index then
     * this should return -1
     *
     * @return int
     */
    function getNextPage();


    /**
     * last page index
     *
     * if list is empty it should return -1
     *
     * @return int
     */
    function getLastPage();


    /**
     * first page link
     * example :
     *  /items?page=1
     *
     * @return iLink
     */
    function getFirstLink();


    /**
     * prev page link
     * example :
     *  /items?page=5
     *
     *
     * @return iLink
     */
    function getPrevLink();

    /**
     * current page link
     * example :
     *  /items?page=3
     *
     *
     * @return iLink
     */
    function getSelfLink();

    /**
     * current page link
     * example :
     *  /items?page=3
     *
     * This should be identical to @see self::getSelfLink
     * the only reason for its existence is syntactical sugar
     *
     * @return iLink
     */
    function getCurrentLink();

    /**
     * current page link
     * example :
     *  /items?page=4
     *
     *
     * @return iLink
     */
    function getNextLink();

    /**
     * current page link
     * example :
     *  /items?page=6
     *
     *
     * @return iLink
     */
    function getLastLink();

}
