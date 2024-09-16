<?php

namespace App\Helpers;

class WeatherHelper
{
    public static function getWeatherIcon($condition)
    {
        $condition = strtolower($condition);

        if (strpos($condition, 'clear') !== false) {
            return 'fas fa-sun';
        } elseif (strpos($condition, 'cloud') !== false) {
            return 'fas fa-cloud';
        } elseif (strpos($condition, 'rain') !== false) {
            return 'fas fa-cloud-showers-heavy';
        } elseif (strpos($condition, 'overcast') !== false) {
            return 'fas fa-cloud-sun';
        } elseif (strpos($condition, 'snow') !== false) {
            return 'fas fa-snowflake';
        } else {
            return 'fas fa-cloud';
        }
    }
}
