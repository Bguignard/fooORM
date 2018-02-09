<?php


class DaoCollection implements CrudDaoCollection
{
    /**
     * @var string
     */
    private $tableName;
    private $typeOfReturn;


    public function __construct($tableName, $typeOfReturn = 'stdClass')
    {
        $this->tableName = $tableName;
        $this->typeOfReturn = $typeOfReturn;
    }


    //tableName can be a view name
    public function selectAll()
    {
        global $con;
        return $con->getArray(true, 'SELECT * FROM ' . $this->tableName . ';', $this->typeOfReturn);
    }

    public function selectWithCondition($condition)
    {
        // TODO: Implement selectWithCondition() method.
    }

}