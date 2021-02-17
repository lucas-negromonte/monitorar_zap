<?php

/*
*  __________________
*  |  	  			|
*  |  EasyCSV 2.0	|
*  |				|
*  |________________|
*  
*/

namespace Source\Support;

class EasyCSV
{

    private $filename;
    private $titles;
    private $rows;

    public function __construct($filename = '')
    {
        $this->filename = $filename . '.csv';
    }


    public function generate($test = '')
    {

        $file = "";

        foreach ($this->titles as $column => $alias) {
            $file .= $alias . ";";
        }
        $file .= "\n";

        $reorderedRows = $this->reorderAssociativeArray($this->rows, $this->titles);

        $newRows = array();
        foreach ($reorderedRows as $key) {
            foreach ($key as $subkey => $subvalue) {
                if (in_array($subkey, array_keys($this->titles))) {
                    $file .= $subvalue . ";";
                }
            }
            $file .= "\r\n";
        }

        if ($test === true) {
            echo $file;
        } else {
            header('Content-Encoding: UTF-8');
            header("Content-type: text/csv; charset=UTF-8");
            echo "\xEF\xBB\xBF";
            header_remove();
            header("Content-type: application/csv");
            header("Content-type: application/force-download");
            header('Content-Disposition: attachment; filename=' . $this->filename . '');
            header("Pragma: no-cache");
            echo $file;
        }
    }


    public function reorderAssociativeArray($array, $indexes)
    {
        $reordered = array();

        foreach ($array as $item) {

            $reorderedItem = array();

            foreach ($indexes as $index => $alias) {
                $reorderedItem[$index] = isset($item->$index) ? $item->$index : null;
            }

            array_push($reordered, $reorderedItem);
        }

        return $reordered;
    }


    public function getTitlse()
    {
        return $this->titles;
    }
    public function setTitles($titles)
    {
        $this->titles = $titles;
    }
    public function getRows()
    {
        return $this->rows;
    }
    public function setRows($rows)
    {
        $this->rows = $rows;
    }
}
