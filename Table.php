<?php

namespace App\Service\Sorting;

class Table
{
    private string $tableName;
    private array $paramOrderByTable = [];
    protected \apimainclass $api;

    public function __construct(\apimainclass $api)
    {
        $this->api = $api;
    }

    public function setTableName(string $tableName):self
    {
        $this->tableName = $tableName;
        $this->init();
        return $this;
    }

    private function init():void
    {
            $sql = 'select
                    rr.id,
                    rr.orderby as "orderBy",
                    rr.update_date as "edit"
                from '.$this->tableName.' rr WHERE rr.client_id = ?
                order by rr.orderby ASC, rr.update_date DESC';
        $this->paramOrderByTable = $this->api->query($sql, [$this->api->currentUserData->clientId], 'array');
    }

    public function getTableName():string
    {
        return $this->tableName;
    }

    public function getOrderByTableResult():array
    {
        return $this->paramOrderByTable;
    }
}