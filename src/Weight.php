<?php

class Weight
{
    private $float;

    public static function fromFloat($float)
    {
        $weight = new Weight();

        $weight->float = $float;

        return $weight;
    }

    public function float()
    {
        return $this->float;
    }

}
