<?php

namespace App\Service\Sorting;

interface TableInterface
{
    public function setTableName(string $tableName):Table;

    public function getTableName():string;

    public function getOrderByTableResult():array;
}