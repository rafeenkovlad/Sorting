<?php

namespace App\Service\Sorting;

interface SortingInterface extends TableInterface
{
    public function go($position): bool;
}