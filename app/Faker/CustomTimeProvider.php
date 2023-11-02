<?php
namespace App\Faker;
use Faker\Provider\Base;

class CustomTimeProvider extends Base
{
    /**
     * Generate a random time on the hour (e.g. 14:00:00)
     *
     * @return string
     */
    public function onTheHour()
    {
       return $this->generator->numberBetween(8, 20) . ':00';
    }

    /**
     * Generate a random time on the half hour (e.g. 14:30:00)
     *
     * @return string
     */
    public function onTheHalfHour()
    {
       return $this->generator->numberBetween(0, 23) . ':30';
    }

    /**
     * Generate a random time on the quarter or half hour (e.g. 14:15:00, 14:45:00, 14:30:00)
     *
     * @return string
     */
    public function onTheQuarterOrHalfHour()
    {
       $hour = $this->generator->numberBetween(0, 23);
       $minute = $this->generator->randomElement([0, 15, 30, 45]);
       return $hour . ':' . $minute;
    }

    /**
     * Generate a start time and end time (both on the hour)
     * 
     * @return array<string, string>
     */ 
    public function startAndEndTime()
    {
        $startTime = $this->onTheHour();
        $endTime = $this->generator->numberBetween((int)substr($startTime,0,2)+1, 21) . ':00';
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}