<?php
//todo do a method to get the type of parameter to use in prepared request + adding '' around strings

class Dao implements CrudDao
{

    /**
     * @return object
     */
    public function selectThis()
    {
        $req = 'SELECT * FROM '. $this->getTableName() . ' WHERE ' . Tools::fromClassNameToTableName($this->getPrimaryKeyName()) . ' = :param1;';
         return Connexion::getPdo()->selectSingleCondition($req, $this->$this->getPrimaryKeyName(), $this->getTypeOfField($this->getPrimaryKeyName()), get_class($this));
    }

    public function insertThis()
    {
        $req = 'INSERT INTO '. $this->getTableName() . ' (' . $this->getFieldsNamesInString() . ') VALUES(' . $this->getFieldsValuesInString() . ');';
    }

    public function deleteThis()
    {
        //todo : finish it
        $req = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . Tools::fromClassNameToTableName($this->getPrimaryKeyName()) . ' = :param1;';

    }

    public function updateThis()
    {
        //todo : finish it
        $req = 'UPDATE ' . $this->getTableName() . ' SET (' .  $this->getFieldsNamesInString() .') VALUES(' . $this->getFieldsValuesInString() . ');';
    }

    /**
     * @return string
     */
    public function getPrimaryKeyName():string
    {
        $req = 'SHOW INDEXES FROM '. $this->getTableName() .' WHERE Key_name=\'PRIMARY\';';
        Connexion::setReq($req);
        return Connexion::getPdo()->query()['Column_name'];
    }

    /**
     * @param $fieldName
     * @return string
     */
    public function getTypeOfField($fieldName):string
    {
        $req = 'SHOW COLUMNS FROM '. $this->getTableName() .' WHERE Field=\'' . $fieldName . '\';';
        Connexion::setReq($req);
        return Connexion::getPdo()->query()['Type'];
    }

    public function getTableName()
    {
        return Tools::fromClassNameToTableName(get_class($this));
    }

    public function getFieldsNamesInString():string
    {
        $first = true;
        $r = '';
        $v = get_class_vars($this);
        foreach ($v as $n => $c){
            if($first)
                $r .= $n;
            else
                $r .= ', ' . $n;
            $first = false;
        }
        return $r;
    }
    public function getFieldsValuesInString():string
    {
        $first = true;
        $r = '';
        $v = get_object_vars($this);
        foreach ($v as $n => $c){
            if($first)
                $r .= $c;
            else
                $r .= ', ' . $c;
            $first = false;
        }
        return $r;
    }

    public function getNameEqualValueInString():string
    {
        $first = true;
        $r = '';
        $v = get_object_vars($this);
        foreach ($v as $n => $c){
            if($first)
                $r .= $v . '=' . $c;
            else
                $r .= ', ' . $v . '=' . $c;
            $first = false;
        }
        return $r;
    }
}