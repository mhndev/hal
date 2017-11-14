<?php
namespace mhndev\hal\tests;

use mhndev\hal\Link;
use mhndev\hal\Presenter;
use mhndev\hal\Resource;
use PHPUnit\Framework\TestCase;

class PresenterTest extends TestCase
{


    function testAsArray()
    {
        $user = ['username' => 'soul_reaper', 'mobile' => '09124971706'];

        $user_resource = new Resource($user);

        $comments = [
            ['text' => 'sample text1', 'uid' => 12],
            ['text' => 'sample text2', 'uid' => 27]
        ];

        $comments_resource = new Resource($comments);
        $user_resource->addEmbeddedResource($comments_resource, 'comments');
        $comments_link = new Link('self', 'http://base_uri/user');
        $user_resource->addLink($comments_link);
        $presenter = new Presenter($user_resource);
        $userArray = $presenter->asArray();

        $this->assertArrayHasKey('_links', $userArray);
        $this->assertArrayHasKey('_embedded', $userArray);


        $this->assertArrayHasKey('comments', $userArray['_embedded']);
        $this->assertArrayHasKey('self', $userArray['_links']);

        $this->assertEquals($comments, $userArray['_embedded']['comments']['data']);
    }



}
