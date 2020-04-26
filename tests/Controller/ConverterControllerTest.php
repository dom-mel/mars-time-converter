<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConverterControllerTest extends WebTestCase
{
    public function testGetWithoutTimestampShouldReturn400(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v1/convert');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $expected = [
            'title' => 'Bad Request',
            'status' => 400,
            'details' => 'date parameter is missing',
        ];
        $this->assertEquals($expected, json_decode($client->getResponse()->getContent(), true));
    }

    public function testWhenDateIsBefore1972ThenStatusCodeIs400(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v1/convert', ['date' => '1900-01-01T00:00:01Z']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $expected = [
            'title' => 'Bad Request',
            'status' => 400,
            'details' => 'Date MUST be after 1972-01-01',
        ];
        $this->assertEquals($expected, json_decode($client->getResponse()->getContent(), true));
    }


    public function testWhenDateIsInvalidThenStatusCodeIs400(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v1/convert', ['date' => 'not a date']);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $expected = [
            'title' => 'Bad Request',
            'status' => 400,
            'details' => 'Invalid date format',
        ];
        $this->assertEquals($expected, json_decode($client->getResponse()->getContent(), true));
    }


    public function testGivenDateThenReturnCorrectResponseWith200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/v1/convert', ['date' => '2000-01-06T00:00:00Z']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $expected = [
            'Mars Sol Date' => 44795.99939787041,
            'Martian Coordinated Time' => '23:59:39',
        ];
        $this->assertEquals($expected, json_decode($client->getResponse()->getContent(), true));
    }
}