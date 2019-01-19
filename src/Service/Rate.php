<?php
namespace App\Service;

class Rate{

    private $directory;

    public function __construct($targetDirectory)
    {
        $this->directory = $targetDirectory;
    }


    //TODO Service calcul moyenne des notes
    public function rateAverage($rates)
    {

        foreach($rates as $rate)
        {

           $average = $rate + $rate / $rate;

        }

        return $average;

    }

}