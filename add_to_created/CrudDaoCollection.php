<?php


interface CrudDaoCollection
{
    public function selectAll();
    public function selectWithCondition($condition);
}