<?php
//require_once('../common.inc');
//include("../peter-backend/DB.php");

class Pagination
{
    private $pageOptions = array();
    private $sql;
    public function __construct($sql, $pageAt, $limitPerPage){
        $showCounts = $this->countRows($sql);
        $pageNum = $this->countPages($showCounts,$limitPerPage);
        foreach($this->rangeWithDots($pageAt,$pageNum) as $value){
            array_push($this->pageOptions,$value);
        }
        $this->sql = $this->paginationQuery($sql,$pageAt,$limitPerPage);
    }

    private function countRows($query){
        $new = "count(*)";
        $start = 'SELECT ';
        $end = ' FROM';
        $sql = preg_replace('#('.preg_quote($start).')(.*)('.preg_quote($end).')#si', '$1'.$new.'$3', $query);
//        echo $sql;
        $pdo = new Db;
        $pdo_obj = $pdo ->pdo();
        $pdostat = $pdo_obj->query($sql);
        $data_matrx = $pdostat->fetchAll();
        $number_of_results = $data_matrx[0][0];
        return $number_of_results;
    }

    private function countPages($showCounts,$limitPerPage){
        return ceil($showCounts/$limitPerPage);
    }

    private function paginationQuery($query, $pageAt, $limitPerPage){

        $this_page_first_result = ($pageAt-1)*$limitPerPage;
        $sql = $query." LIMIT ".$this_page_first_result.", "."$limitPerPage";
//        echo $sql;

        return $sql;
    }

    //$c = $pageAt, $m =$pageNum;
    private function rangeWithDots($c, $m)
    {
        $current = $c;
        $last = $m;
        $delta = 2;
        $left = $current - $delta;
        $right = $current + $delta + 1;
        $range = array();
        $rangeWithDots = array();
        $l = -1;

        for ($i = 1; $i <= $last; $i++)
        {
            if ($i == 1 || $i == $last || $i >= $left && $i < $right)
            {
                array_push($range, $i);
            }
        }

        for($i = 0; $i<count($range); $i++)
        {
            if ($l != -1)
            {
                if ($range[$i] - $l === 2)
                {
                    array_push($rangeWithDots, $l + 1);
                }
                else if ($range[$i] - $l !== 1)
                {
                    array_push($rangeWithDots, '...');
                }
            }

            array_push($rangeWithDots, $range[$i]);
            $l = $range[$i];
        }

        return $rangeWithDots;
    }

    public function getPageOptions(){
        return $this->pageOptions;
    }
    public function getSql(){
        return $this->sql;
    }


}