<?php

interface CrudDao
{
    public function selectThis();

    public function insertThis();

    public function deleteThis();

    public function updateThis();

    public function getTableName();

    public function getPrimaryKeyName();

    public function getPrimaryKeyValue();
}