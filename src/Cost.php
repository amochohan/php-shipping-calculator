<?php

class Cost
{
    private $float;

    public static function fromFloat($float)
    {
        $cost = new Cost();

        $cost->float = $float;

        return $cost;
    }

    public function float()
    {
        return $this->float;
    }

    public function equals(Cost $anotherCost)
    {
        return $this->float == $anotherCost->float;
    }
}
