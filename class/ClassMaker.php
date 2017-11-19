<?php

class ClassMaker
{
    private $tableArray = [];
    private $pdo = null;

    public function __construct($dbName, $userName, $password)
    {
        $this->pdo = Connexion::getPdo($dbName, $userName, $password);
    }

    public function makeFiles()
    {
        $this->getTables();
        if(count($this->tableArray)==0) return 'Base inexistante';
        $str = '';

        foreach ($this->tableArray as $table){
            $columnTabs = $this->getColumns($table);
            $file = $this->classWritter($table, $columnTabs);
            $str .= $file;
        }
        return $str;
    }

    public function classWritter($name, $attributes)
    {
        $endl = '\r';
        $endl = '<br />';
        $str = '';
        $str .= 'class '. Tools::fromTableNameToClassName($name) . '{' . $endl;
        foreach ($attributes as $attribute) {
            $str .= '/**' . $endl . '
                * @var ' . $attribute->Type . $endl .
            '**/' . $endl;
            $str .= '    private $' . $attribute->Field . ';' . $endl;
        }
        $str .= '}' . $endl . $endl;
        return $str;
    }

    //getting all tables
    public function getTables()
    {
        $this->pdo->setReq("SHOW TABLES");
        $this->tableArray = $this->pdo->query()->tableTab();

    }

    public function getColumns($tableName)
    {
//        if(sizeof($this->tableArray)==0) return "";
        $req = 'SHOW COLUMNS FROM '. $tableName .';';
        $this->pdo->setReq($req);
        return $this->pdo->query()->tab();
    }

    //show tables

}