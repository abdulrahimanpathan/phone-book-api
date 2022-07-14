<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ContactTest extends TestCase
{
    /**
     * test add contact success
     */
    public function testAddContactSuccess(): void
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]); 
        $data = array(
            'first_name' => 'abdul',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );

        $response = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        $newContact = json_decode($response->getBody(true), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertArrayHasKey('first_name', $newContact);

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }
    /**
     * test add contact 400 response
     */
    public function testAddContactFailWith400(): void
    {
        $this->expectException(ClientException::class);
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]); 
        $data = array(
            'first_name' => 'abdul',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => "1234567",
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );
        $response = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        $duplicate = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        
        $newContact = json_decode($duplicate->getBody(true), true);
        $this->assertEquals(400, $duplicate->getStatusCode());
    }
    /**
     * test get contact by name success
     */
    public function testGetContactByName200(): void
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $data = array(
            'first_name' => 'ramesh',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );

        $createContact = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        $response = $client->get('/api/contact/ram');
        $responseBody = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('first_name', $responseBody[0]);
    }
    /**
     * test get contact by name no content
     */
    public function testGetContactByName204(): void
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $response = $client->get('/api/contact/ramd');

        $this->assertEquals(204, $response->getStatusCode());
    }
    /**
     * test update contact success
     */
    public function testUpdateContactSuccess(): void
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]); 
        $data = array(
            'first_name' => 'abdul',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );

        $response = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        $newContact = json_decode($response->getBody(true), true);

        $newData = array(
            'first_name' => 'sabida',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );
        $update = $client->put('/api/contact/'.$newContact['id'], [
            'body' => json_encode($newData)
        ]);
        $updatedData = json_decode($update->getBody(true), true);
        $this->assertEquals(200, $update->getStatusCode());
        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertArrayHasKey('first_name', $updatedData);
    }
    /**
     * test update contact fail with 404 not found
     */
    public function testUpdateContactFail(): void
    {
        $this->expectException(ClientException::class);
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $newData = array(
            'first_name' => 'sabida',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );
        $update = $client->put('/api/contact/'.rand(), [
            'body' => json_encode($newData)
        ]);
        $updatedData = json_decode($update->getBody(true), true);
        $this->assertEquals(404, $update->getStatusCode());
    }
    /**
     * test delete contact success
     */
    public function testDeleteContactSuccess(): void
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]); 
        $data = array(
            'first_name' => 'abdul',
            'last_name' => 'patan',
            'address_information' => 'test address',
            'phone_number' => rand(),
            'birthday' => '1991-03-21',
            'email_address' => 'abdul@gmail.com',
            'picture' => 'http://127.0.0.1:8000/mule certifications.jpeg'
        );

        $response = $client->post('/api/contact', [
            'body' => json_encode($data)
        ]);
        $newContact = json_decode($response->getBody(true), true);

        $delete = $client->delete('/api/contact/'.$newContact['id']);
        $this->assertEquals(204, $delete->getStatusCode());
    }
    /**
     * test delete contact fail
     */
    public function testDeleteContactFail(): void
    {
        $this->expectException(ClientException::class);
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $delete = $client->delete('/api/contact/'.rand());
        $this->assertEquals(404, $delete->getStatusCode());
    }
}
