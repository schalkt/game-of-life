<?php

namespace Gol;

/**
 * Class Grid
 */
class Grid
{

    /**
     * Grid width
     * @var int
     */
    public $width;

    /**
     * Grid height
     * @var int
     */

    public $height;

    /**
     * Grid array
     * @var array
     */
    public $cells = [];

    /**
     * Grid constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Calculate the grid dimension
     */
    public function calcDimension()
    {

        $this->width = count($this->cells[0]);
        $this->height = count($this->cells);

    }

    /**
     * Generate the cells
     *
     * @param $seed
     * @param int $density
     * @return $this
     */
    public function generate($seed, $density = 10)
    {

        mt_srand($seed);

        for ($x = 0; $x < $this->width; $x++) {
            for ($y = 0; $y < $this->height; $y++) {
                if ($seed) {
                    $this->cells[$y][$x] = (mt_rand(0, $density) === 0);
                } else {
                    $this->cells[$y][$x] = 0;
                }
            }
        }

        return $this;

    }

}