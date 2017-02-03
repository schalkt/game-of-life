<?php

namespace Gol;

/**
 * Class Loader
 */
class Loader
{

    /**
     * @var array
     */
    private $parsed = [];
    /**
     * @var null
     */
    private $width = null;
    /**
     * @var null
     */
    private $height = null;

    /**
     * @param $filename
     * @return array|void
     */
    public function loadLif($filename)
    {

        $filepath = PATH_APP . '/app/storage/lifs' . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filepath)) {
            return;
        }

        $cells = $this->parseLif(file_get_contents($filepath));

        return $cells;

    }


    private function fillCells()
    {

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $this->cells[$y][$x] = 0;
            }
        }
    }

    /**
     * @param $content
     * @return array
     */
    private function parseLif($content)
    {

        $this->width = 0;
        $this->height = 0;
        $rows = explode(PHP_EOL, $content);
        $cells = [];

        foreach ($rows as $rowNumber => $row) {
            $this->parseLifRow($row);
        }

        $this->height = sizeof($this->parsed);
        $this->fillCells();

        for ($y = 0; $y < $this->height; $y++) {

            $offsetY = round($this->height / 2) + $this->parsed[$y]['offset'][1];
            $cells[$offsetY] = isset($cells[$offsetY]) ? $cells[$offsetY] : [];

            for ($x = 0; $x < $this->width; $x++) {

                $offsetX = round($this->width / 2) + $this->parsed[$y]['offset'][0];
                $cells[$offsetY][$offsetX] = $this->parsed[$y]['items'][$x] ? 1 : 0;


            }

        }

        return array_values($cells);

    }

    /**
     * @param $row
     */
    private function parseLifRow($row)
    {

        static $coord;

        $cmd = substr($row, 0, 2);

        if ($cmd == '#P') {
            $parts = explode(' ', trim($row));
            $coord = [$parts[1], $parts[2]];
        }

        if ($cmd{0} != '#') {

            $this->parsed[] = [
                'offset' => [$coord[0], $coord[1]],
                'items' => str_split(rtrim($row)),
            ];

            $this->width = max($this->width, strlen($row));

        }


    }

}