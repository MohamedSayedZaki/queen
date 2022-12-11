<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogViewerTest extends WebTestCase
{

    public function testCheckFileIsEmpty(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/d.log']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(302, $response['status']);

    }

    public function testCheckFileIsNotExist(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/d.lo']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(301, $response['status']);

    }    

    public function testGetFileFirstPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/transcript.txt','page' => 0, 'type' => 'first']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['status']);

    }    
    
    public function testGetFileNextPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/transcript.txt','page' => 1, 'type' => 'next']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['status']);

    }        

    public function testGetFilePreviousPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/transcript.txt','page' => 0, 'type' => 'previous']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['status']);

    }
    
    public function testGetFileLastPage(): void
    {
        $client = static::createClient();
        $client->request('POST', '/getAction',['path' => '/var/www/site/var/log/transcript.txt', 'type' => 'end']);
        
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['status']);

    }    
}