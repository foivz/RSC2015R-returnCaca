<?php

namespace AppBundle\Helper;

class LatLngHelper
{
    public static function getRandomLatLngNear($latitude, $longitude)
    {
//        $longitude = (float)18.695897;
//        $latitude = (float)45.554386;
        $radius = rand(1, 10) / 200; // in miles

        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        $decimals = 10000000000000000;

        return [
            'lat' => rand($lat_min * $decimals, $lat_max * $decimals) / $decimals,
            'lng' => rand($lng_min * $decimals, $lng_max * $decimals) / $decimals,
        ];
    }
}