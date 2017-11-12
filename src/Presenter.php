<?php
namespace mhndev\hal;

use mhndev\hal\Contract\iLink;
use mhndev\hal\Contract\iResource;

/**
 * Class Presenter
 * @package mhndev\hal\Presenter
 */
class Presenter
{

    /**
     * @var iResource
     */
    protected $resource;


    /**
     * Presenter constructor.
     * @param iResource $resource
     */
    function __construct(iResource $resource)
    {
        $this->resource = $resource;
    }


    /**
     * @return mixed
     */
    function asArray()
    {
        return $this->toArray($this->resource);
    }


    /**
     * @return string
     */
    function asJson()
    {
        return json_encode($this->asArray());
    }


    function asXml()
    {
        return 'Not implemented yet !';
    }


    /**
     * @param iResource $resource
     * @return array
     */
    protected function toArray(iResource $resource)
    {
        $result = $resource->getProperties();

        if($resource->hasLink()){

            $result['_links'] = [];
            $links = $resource->getLinks();

            /** @var iLink $link */
            foreach ($links as $link){
                array_push($result['_links'], $this->linkToArray($link) );
            }
        }

        if($resource->hasEmbedded()){

            $result['_embedded'] = [];
            $embeddeds = $resource->getEmbedded();

            /** @var iResource $embedded */
            foreach ($embeddeds as $key => $embedded){
                $result['_embedded'][$key] = $this->toArray($embedded);
            }
        }

        return $result;
    }


    /**
     * @param iLink $link
     * @return array
     */
    protected function linkToArray(iLink $link)
    {
        $result = [];

        $result[$link->getRelation()] = [
            'href' => $link->getHref()
        ];

        if($link->isTemplated()){
            $result[$link->getRelation()] = [
                'templated' => $link->getTemplated()
            ];
        }

        if($link->hasName()){
            $result[$link->getName()] = [
                'name' => $link->getName()
            ];
        }


        return $result;
    }


}
