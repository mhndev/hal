<?php
namespace mhndev\hal\tests;

use mhndev\hal\Link;
use mhndev\hal\Resource;
use PHPUnit\Framework\TestCase;

/**
 * Class ResourceTest
 * @package mhndev\hal\tests
 */
class ResourceTest extends TestCase
{


    function testHasLink()
    {
        $user = ['username' => 'soul_reaper', 'mobile' => '09124971706'];

        $user_resource = new Resource($user);

        $comments_link = new Link('comments', 'http://base_uri/user/comments');

        $user_resource->addLink($comments_link);
        $this->assertTrue($user_resource->hasLink());
        $user_resource->removeLink($comments_link);
        $this->assertFalse($user_resource->hasLink());
    }



    function testHasEmbedded()
    {
        $user = ['username' => 'soul_reaper', 'mobile' => '09124971706'];

        $user_resource = new Resource($user);

        $comments = [
            ['text' => 'sample text1', 'uid' => 12],
            ['text' => 'sample text2', 'uid' => 27]
        ];

        $comments_resource = new Resource($comments);

        $user_resource->addEmbeddedResource($comments_resource, 'comments');
        $this->assertTrue($user_resource->hasEmbedded());
        $user_resource->removeEmbeddedByIndex('comments');
        $this->assertFalse($user_resource->hasEmbedded());
    }


    function testAddEmbedded()
    {
        $user = ['username' => 'soul_reaper', 'mobile' => '09124971706'];

        $user_resource = new Resource($user);

        $comments = [
            ['text' => 'sample text1', 'uid' => 12],
            ['text' => 'sample text2', 'uid' => 27]
        ];

        $comments_resource = new Resource($comments);

        $user_resource->addEmbeddedResource($comments_resource, 'comments');
        $this->assertTrue($user_resource->hasEmbedded());
    }




}
