<?php

namespace Tests\Unit;

use App\Services\FlightsService;
use PHPUnit\Framework\TestCase;

class FlightsTest extends TestCase
{

    public $flights_1 = [
        ['id' => 1, 'code_departure' => 'MIL', 'code_arrival' => 'ROM', 'price' => 50],
        ['id' => 2, 'code_departure' => 'MIL', 'code_arrival' => 'ROM', 'price' => 100],
        ['id' => 3, 'code_departure' => 'MIL', 'code_arrival' => 'ROM', 'price' => 150],
    ];


    public $flights_2 = [
        ['id' => 1, 'code_departure' => 'MIL', 'code_arrival' => 'ROM', 'price' => 300],
        ['id' => 2, 'code_departure' => 'MIL', 'code_arrival' => 'PAR', 'price' => 50],
        ['id' => 3, 'code_departure' => 'MIL', 'code_arrival' => 'PAR', 'price' => 100],
        ['id' => 4, 'code_departure' => 'PAR', 'code_arrival' => 'ROM', 'price' => 50],
        ['id' => 5, 'code_departure' => 'PAR', 'code_arrival' => 'ROM', 'price' => 250],
    ];


    public $flights_3 = [
        ['id' => 1, 'code_departure' => 'MIL', 'code_arrival' => 'ROM', 'price' => 300],
        ['id' => 2, 'code_departure' => 'MIL', 'code_arrival' => 'PAR', 'price' => 50],
        ['id' => 3, 'code_departure' => 'MIL', 'code_arrival' => 'PAR', 'price' => 100],
        ['id' => 4, 'code_departure' => 'PAR', 'code_arrival' => 'ROM', 'price' => 50],
        ['id' => 5, 'code_departure' => 'PAR', 'code_arrival' => 'ROM', 'price' => 250],
        ['id' => 6, 'code_departure' => 'LON', 'code_arrival' => 'MIL', 'price' => 50],
        ['id' => 7, 'code_departure' => 'LON', 'code_arrival' => 'ROM', 'price' => 200],
        ['id' => 8, 'code_departure' => 'LON', 'code_arrival' => 'ROM', 'price' => 10],
        ['id' => 9, 'code_departure' => 'MIL', 'code_arrival' => 'LON', 'price' => 200],
        ['id' => 10, 'code_departure' => 'MIL', 'code_arrival' => 'LON', 'price' => 150],
        ['id' => 11, 'code_departure' => 'PAR', 'code_arrival' => 'LON', 'price' => 10],
        ['id' => 12, 'code_departure' => 'PAR', 'code_arrival' => 'LON', 'price' => 30],
        ['id' => 13, 'code_departure' => 'ROM', 'code_arrival' => 'LON', 'price' => 150],
        ['id' => 14, 'code_departure' => 'ROM', 'code_arrival' => 'PAR', 'price' => 10],
        ['id' => 15, 'code_departure' => 'LON', 'code_arrival' => 'MIL', 'price' => 30],
    ];


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_lowest_price_with_no_stopovers()
    {

        $flightsService = new FlightsService($this->flights_1, 'MIL', 'ROM');

        $flights = $flightsService->getFlights();

        $price = array_pop($flights); // remove total price
        $results = collect($flights)->pluck('id')->toArray();

        $this->assertTrue($price === 50);
        $this->assertTrue($results === [1]);
        $this->assertTrue($flights[0]['code_departure'] === 'MIL' && $flights[0]['code_arrival'] === 'ROM');
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_lowest_price_with_one_stopover()
    {

        $flightsService = new FlightsService($this->flights_2, 'MIL', 'ROM');

        $flights = $flightsService->getFlights();

        $price = array_pop($flights);
        $results = collect($flights)->pluck('id')->toArray();

        $this->assertTrue($price === 100);
        $this->assertTrue($results === [2,4]);
        $this->assertTrue($flights[0]['code_departure'] === 'MIL' && $flights[1]['code_arrival'] === 'ROM');
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_lowest_price_with_two_stopovers()
    {

        $flightsService = new FlightsService($this->flights_3, 'MIL', 'ROM');

        $flights = $flightsService->getFlights();

        $price = array_pop($flights);
        $results = collect($flights)->pluck('id')->toArray();

        $this->assertTrue($price === 70);
        $this->assertTrue($results === [2,11,8]);
        $this->assertTrue($flights[0]['code_departure'] === 'MIL' && $flights[2]['code_arrival'] === 'ROM');
    }
}
