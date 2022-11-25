<?php

namespace App\Service\Sorting;

class Sorting extends Table implements SortingInterface
{

    private const UP = 'up';
    private const DOWN = 'down';


    private function checkPosition($position):void
    {
        $position = mb_strtolower($position);
        if(!in_array($position, [self::DOWN, self::UP], true)) {
            Throw new \RuntimeException('Undefined params position '. $position);
        }
    }

    public function go($position):bool
    {
        $this->checkPosition($position);

        $result = $this->getOrderByTableResult();

        $temp = null;
        $from = null;
        $tempKey = null;
        foreach ($result as $key => ['id' => $id, 'orderBy' => $orderBy]) {
            if ($temp['orderBy'] === $orderBy) {
                if ($position === 'up') {
                    $from = $key;
                }

                if ($position === 'down') {
                    $result[$tempKey] = ['id' => $id, 'orderBy' => $orderBy];
                    $result[$key] = $temp;
                    $from = $key;
                }

                break;
            }

            $temp = ['id' => $id, 'orderBy' => $orderBy];
            $tempKey = $key;
        }

        if (is_null($from)) {
            return false;
        }


        $filter = array_slice($result, $from);

        $beforeOrderBy = null;
        foreach ($filter as $key => $item) {
            if ($beforeOrderBy === null || $beforeOrderBy === $item['orderBy']) {
                $filter[$key]['orderBy'] = $beforeOrderBy = $item['orderBy'] + 1;
                $filter[$key]['update'] = true;
                continue;
            }
            $beforeOrderBy = $item['orderBy'];
            $filter[$key]['update'] = false;
        }


        foreach ($filter as ['id' => $id, 'orderBy' => $orderBy, 'update' => $update]) {
            if ($update) {
                $params = ['id' => $id, 'orderBy' => $orderBy];
                $this->api->query('UPDATE '.$this->getTableName().' SET orderby = :orderBy WHERE id = :id', $params);
            }
        }

        return true;
    }
}