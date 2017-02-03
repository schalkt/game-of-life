<?php

namespace Gol;

/**
 * Class Game
 */
class Game
{

    /**
     * Game options
     * @var array
     */
    private $options = [];

    /**
     * Default options
     * @var array
     */
    private static $defaults = [
        'step' => 0,
        'width' => 80,
        'height' => 25,
        'seed' => 1,
        'density' => 7,
        'lif' => null,
        'import' => null,
    ];

    /**
     * Game constructor.
     * @param array $options
     */
    public function __construct($options = array())
    {

        $this->options = array_replace(self::$defaults, (array)$options);
        $this->grid = new Grid($this->options['width'], $this->options['height']);

        if (!empty($this->options['import'])) {

            $this->importJSON($this->options['import']);

        } elseif (!empty($this->options['lif'])) {

            $this->loadLif($this->options['lif']);

        } else {

            $this->grid->generate($this->options['seed'], $this->options['density']);

        }


        if (!empty($this->options['step'])) {
            for ($i = 0; $i <= $this->options['step']; $i++) {
                $this->step();
            }
        }

    }

    /**
     * Calculate the next step
     */
    public function step()
    {

        $height = $this->grid->height;
        $width = $this->grid->width;
        $cells = &$this->grid->cells;

        $kill = [];
        $born = [];

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {

                $neighbor_count = $this->getAliveNeighborCount($x, $y);

                if ($cells[$y][$x] && ($neighbor_count < 2 || $neighbor_count > 3)) {
                    $kill[] = [$y, $x];
                }

                if (!$cells[$y][$x] && $neighbor_count === 3) {
                    $born[] = [$y, $x];
                }

            }
        }

        foreach ($kill as $k) {
            $cells[$k[0]][$k[1]] = 0;
        }

        foreach ($born as $b) {
            $cells[$b[0]][$b[1]] = 1;
        }

    }

    /**
     * Get living neighbors
     *
     * @param $x
     * @param $y
     *
     * @return int
     */
    private function getAliveNeighborCount($x, $y)
    {
        $alive_count = 0;

        for ($y2 = $y - 1; $y2 <= $y + 1; $y2++) {

            if ($y2 < 0 || $y2 >= $this->grid->height) {
                continue;
            }

            for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {

                if ($x2 == $x && $y2 == $y) {
                    continue;
                }

                if ($x2 < 0 || $x2 >= $this->grid->width) {
                    continue;
                }

                if ($this->grid->cells[$y2][$x2]) {
                    $alive_count += 1;
                }
            }
        }

        return $alive_count;
    }


    /**
     * Renders the grid
     */
    public function render()
    {

        $output = '';
        $cli = PHP_SAPI == 'cli';

        foreach ($this->grid->cells as $y => $row) {
            foreach ($row as $x => $cell) {
                $output .= ($cell ? 'O' : '+');
            }
            $output .= $cli ? PHP_EOL : '<br />';
        }

        return $output;

    }

    /**
     * Export grid to JSON
     *
     * @return string
     */
    public function exportJSON()
    {

        return json_encode($this->grid->cells);

    }

    /**
     * Import grid from json
     *
     * @param $json
     */
    public function importJSON($json)
    {

        $this->grid->cells = json_decode($json);
        $this->grid->calcDimension();

    }

    /**
     * Load lif file
     * @param $filename
     */
    private function loadLif($filename)
    {

        $loader = new Loader($this->grid);
        $this->grid->cells = $loader->loadLif($filename);
        $this->grid->calcDimension();

    }


}