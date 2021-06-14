<?php

namespace App\Services;


class FlightsService {


    /**
     * 
     * the resulting flights combinations from dep to arr
     * 
     * @var Array; 
     */
    private $flights;

    /**
     * 
     */
    public function __construct(Array $data, String $departure, String $arrival){
        $this->flights = $this->getFlightsCombinations($data, $departure, $arrival);
    }


    /**
     * Helper function for code duplication
     *
     * @param Array $combinations
     * @param Array $items
     * @return Array
     */
    private function updateCombinationsArray(Array $combinations, Array $items) : Array {
        $total_price = array_sum(array_column($items, 'price'));
        $combinations[] = array_merge($items, ['total_price' => $total_price]);
        return $combinations;
    }



    /**
     * This functions matches flights up to two stopovers (3 flights) between 2 given destinations
     * and calculates the total cost of each combination
     * 
     * Each flight must have 3 mandatory fields DEPARTURE, ARRIVAL, PRICE
     * IE: MIL,ROM,200
     *
     * @param Array $data
     * @param String $dep
     * @param String $arr
     * @return Array
     */
    private function getFlightsCombinations(Array $data, String $dep, String $arr) : Array {
        $departures = [];
        $arrivals = [];
        $combinations = [];

        // get all flights from $departure
        foreach($data as $flight) {
            if ($flight['code_departure'] === $dep) {
                $departures[] = $flight;
            }
        }


        // and all flights from $arrival
        foreach($data as $flight) {
            if ($flight['code_arrival'] === $arr) {
                $arrivals[] = $flight;
            }
        }

        

        foreach($departures as $dep_flight) {
            // 1) no stopovers -> try to find a direct matching flight
            if ($dep_flight['code_arrival'] === $arr) {
                $combinations[] = [$dep_flight, 'total_price' => $dep_flight['price']];
            } else {
                foreach($arrivals as $arr_flight) {

                    // 2) one stopover -> two flight needed to reach $arrival
                    if ($dep_flight['code_arrival'] === $arr_flight['code_departure']) {
                        $combinations = $this->updateCombinationsArray($combinations, [$dep_flight, $arr_flight]);
                    } else {

                        // 3) two stopover -> three flight needed
                        // find all matching "intermediate" flight using the shortcode of the destination
                        // ie: departure flight: ...... MIL-LON
                        // arrival flight: ............ PAR-ROM 
                        // search for: ................ LON-PAR
                        
                        $available_flights = array_filter($data, function($item) use($dep_flight, $arr_flight, $dep) {
                            return $item['code_departure'] === $dep_flight['code_arrival'] && $item['code_arrival'] === $arr_flight['code_departure'] && $item['code_arrival'] !== $dep;
                        });

                        if (!empty($available_flights)) {
                            foreach($available_flights as $available_flight) {
                                $combinations = $this->updateCombinationsArray($combinations, [$dep_flight, $available_flight, $arr_flight]);
                            }
                        }
                    }
                }
            }
        }

        return $combinations;
    }


    /**
     * returns first element of sorted array (cheapest flight)
     *
     * @return Array
     */
    public function getFlights() : Array {
        return collect( $this->flights )->sortBy('total_price')->first();
    }

}