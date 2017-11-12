<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iLink;

/**
 * Class Link
 * @package mhndev\hal
 */
class Link implements iLink
{

    /**
     * @var string
     */
    protected $relation;

    /**
     * @var string
     */
    protected $href;

    /**
     * optional parameter
     *
     * @var string
     */
    protected $name = null;


    /**
     * optional parameter
     *
     * @var boolean
     */
    protected $templated = null;


    /**
     * Link constructor.
     * @param string $relation
     * @param string $href
     * @param array $options
     */
    function __construct(string $relation, string $href, array $options = [])
    {
        $this->relation = $relation;
        $this->href = $href;

        // setting all options

        if(!empty($options)){
            foreach ($options as $option_name => $option_value){
                $this->setOption($option_name, $options);
            }
        }

    }

    /**
     * @param string $option_name
     * @param array  $options
     * @return $this
     */
    private function setOption($option_name, array $options)
    {
        if(array_key_exists($option_name, $options) && $options[$option_name]){
            $this->{$option_name} = $options[$option_name];
        }

        return $this;
    }


    /**
     * @return string
     */
    function getRelation()
    {
        return $this->relation;
    }


    /**
     * @return string
     */
    function getHref()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    function hasName()
    {
        return !empty($this->name);
    }


    /**
     * @return bool
     */
    function isTemplated()
    {
        return ($this->templated == true) ? true : false;
    }

    /**
     * @return boolean | null
     */
    function getTemplated()
    {
        return $this->templated;
    }

}
