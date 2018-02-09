<?php

class Dao implements CrudDao
{

    public function getRequest($typeOfRequest = 'select')
    {
        $req = '';
        if($typeOfRequest == 'select'){
            $req = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ' . $this->getPrimaryKeyName() . '=:' . $this->getPrimaryKeyName() . ';';
        }
        elseif($typeOfRequest == 'insert'){
            $tab = $this->getItemArray();
            unset($tab[$this->getPrimaryKeyName()]);
            $req = 'INSERT INTO ' . $this->getTableName() . ' (' . $this->generateFieldsString($tab) . ') VALUES (' . $this->generateFieldsStringParams($tab) . ');';
        }
        elseif($typeOfRequest == 'update'){
            $tab = [];
            $tab2 = $this->getItemArray();
            //removing primary key
            unset($tab2[$this->getPrimaryKeyName()]);
            foreach ($tab2 as $key => $val) {
                $tab[] = $key . '=:' . $key;
            }
            $req = 'UPDATE ' . $this->getTableName() . ' SET ' . implode(',', $tab) . ' WHERE ' . $this->getPrimaryKeyName() . '=:' . $this->getPrimaryKeyName() . ';';
        }
        elseif($typeOfRequest == 'delete'){
            $req = 'DELETE FROM ' . $this->getTableName() . ' WHERE ' . $this->getPrimaryKeyName() . '=:' . $this->getPrimaryKeyName() . ';';
        }
        else{
            $req = 'SELECT * FROM ' . $this->getTableName() . ' WHERE ' . $this->getPrimaryKeyName() . '=:' . $this->getPrimaryKeyName() . ';';
        }
        return $req;
    }

    public function getParams($typeOfRequest = 'select')
    {
        $tab = [];
        if($typeOfRequest == 'select'){
            $tab = [':' . $this->getPrimaryKeyName() => $this->getPrimaryKeyValue()];
        }
        elseif($typeOfRequest == 'insert'){
            $tab = $this->generateParamsAndValuesTab();
            unset($tab[':' . $this->getPrimaryKeyName()]);
        }
        elseif($typeOfRequest == 'update'){
            $tab = $this->generateParamsAndValuesTab();
            unset($tab[':' . $this->getPrimaryKeyName()]);
            $tab[':' . $this->getPrimaryKeyName()] = $this->getPrimaryKeyValue();
        }
        elseif($typeOfRequest == 'delete'){
            $tab = [':' . $this->getPrimaryKeyName() => $this->getPrimaryKeyValue()];
        }
        else{
            $tab = [':' . $this->getPrimaryKeyName() => $this->getPrimaryKeyValue()];
        }
        return $tab;
    }


    /**
     * @return object
     */
    public function selectThis()
    {
        global $con;
        $req = $this->getRequest('select');
        $paramTab = $this->getParams('select');
        $rt = $con->getArray(true, $req, $paramTab, get_class($this));
        if(count($rt) > 0)
            return $rt[0];
        else
            return null;
    }

    public function insertThis($lastId = false)
    {
        global $con;
        $req = $this->getRequest('insert');
        $paramTab = $this->getParams('insert');
        $con->xeq(true, $paramTab, $req);
        if($lastId){
            return $con->getLastInsertedId();
        }
    }

    public function updateThis()
    {
        global $con;
        $req = $this->getRequest('update');
        $paramTab = $this->getParams('update');
        return $con->xeq(true, $paramTab, $req);
    }

    public function deleteThis()
    {
        global $con;
        $req = $this->getRequest('delete');
        $paramTab = $this->getParams('delete');
        return $con->xeq(true, $paramTab, $req);
    }


    /**
     * @return string
     */
    public function getPrimaryKeyName()
    {
        //has to be overriden
        return '';
    }
    public function getPrimaryKeyValue()
    {
        //has to be overriden
        return null;
    }

    public function getTableName()
    {
        //has to be overriden
        return '';
    }

    //to return all the fields array = $this->getItemArray()
    public function generateFieldsStringParams($array):string
    {
        return ':' . implode(', :', array_keys($array));
    }

    //to return all the fields array = $this->getItemArray()
    public function generateFieldsString($array):string
    {
        return implode(', ', array_keys($array));
    }

    public function generateParamsAndValuesTab()
    {
        $arr = [];
        foreach (get_object_vars($this) as $attrName => $attrValue){
            $arr[':' . $attrName] = $attrValue;
        }
        return $arr;
    }

    public function generateParamKnownTab($fieldName = 'undefined')
    {
        $arr = [];
        foreach (get_object_vars($this) as $attrName => $attrValue){
            if($attrName == $fieldName){
                $arr[':' . $attrName] = false;
            }
            else{
                $arr[':' . $attrName] = true;
            }
        }
        return $arr;
    }
    public function getItemArray()
    {
        return get_object_vars($this);
    }
}