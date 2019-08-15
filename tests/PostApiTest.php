<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostApiTest extends AbstractApiWebTestCase
{
    public function testCreateNewPost()
    {
        $data = [
            'title' => 'Post title!',
            'publicationDate' => '2019-08-15T18:19:28.901Z',
            'content' => 'This is the post content.',
        ];

        $response = $this->request('POST', '/api/posts', $data);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/ld+json; charset=utf-8', $response->headers->get('Content-Type'));
    }
}
