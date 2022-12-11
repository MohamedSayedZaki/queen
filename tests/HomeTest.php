<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{

    public function testNotValidLogin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login',['username' => 'admin', 'password' => 'fdf']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(205, $response['status']);
    }

    public function testValidLogin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login',['username' => 'admin', 'password' => 'admin']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['status']);
    }
}
