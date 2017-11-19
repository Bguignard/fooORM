<?php

class ClassMaker
{
    private $tableArray = [];
    private $pdo = null;
    private $dir = '../../created';

    public function __construct($dbName, $userName, $password)
    {
        $this->pdo = Connexion::getPdo($dbName, $userName, $password);
        if(!is_dir($this->dir))
            mkdir('created');
    }

    public function makeFiles()
    {
        $result = [];
        $this->getTables();
        if(count($this->tableArray)==0) return 'Base inexistante';

        foreach ($this->tableArray as $table){
            $columnTabs = $this->getColumns($table);
            $file = fopen($this->dir . '/' . Tools::fromTableNameToClassName($table) . '.php','w') or die(array_push($result, "Erreur pour la table " . $table));
            fwrite($file, $this->classWritter($table, $columnTabs));
            fclose($file);
            array_push($result, 'Création de la classe associée à la table ' . $table);
        }
        return json_encode($result);
    }

    //todo : ajouter la classe connexion et la classe DAO
    public function classWritter($name, $attributes)
    {
        $endl = "\r";
        $endlTab = $endl . '    ';
        $endlTab2 = $endlTab . '    ';
        $strGtrStr = '';
        $str = '<?php ' .$endl . ' class '. Tools::fromTableNameToClassName($name) . ' extends DAO' . $endl . '{' . $endlTab;
        $primaryKeyName = '';
        $primaryKeyType = '';
        foreach ($attributes as $attribute) {
            if($attribute->Field == $this->getPrimaryKeyName($name)){
                $primaryKeyName = $attribute->Field;
                $primaryKeyType = $this->fromMySqlToPhpTypes($attribute->Type);
            }
            //incrementing attributes
            $str .= '/**' . $endlTab;
            $str .= '* Sql type :  ' . $attribute->Type . $endlTab;
            $str .= '* @var ' . $this->fromMySqlToPhpTypes($attribute->Type) . $endlTab;
            $str .= '**/' . $endlTab;
            $str .= 'private $' . $attribute->Field . ';' . $endlTab;

            //incrementing getters
            $strGtrStr .= 'public function get' . ucfirst($attribute->Field) . '(){' . $endlTab2 . 'return $this->' . $attribute->Field . ';' . $endlTab . '}' . $endlTab;
            //incrementing setters
            $strGtrStr .= 'public function set' . ucfirst($attribute->Field) . '($' . $attribute->Field . '){' . $endlTab2 . '$this->' . $attribute->Field . ' = $' . $attribute->Field . ';' . $endlTab . '}' . $endlTab;
        }
        //incrementing constructor
        $str .= $endlTab . '//Constructor' . $endlTab;
        $str .= 'public function __construct($'.$primaryKeyName.' = ' . $this->getDefaultValueFromType($primaryKeyType) . '){' . $endlTab2 . '$this->' . $primaryKeyName . ' = $' . $primaryKeyName . ';' . $endlTab . '}' . $endl .$endlTab;

        //incrementing functions
        $str .= '//DAO basic functions' . $endlTab;
        $str .= 'public function getTableName(){' . $endlTab2 . 'return \'' . $name . '\';' . $endlTab . '}' . $endlTab;
        $str .= 'public function getPrimaryKeyName(){' . $endlTab2 . 'return \'' . $this->getPrimaryKeyName($name) . '\';' . $endlTab . '}' . $endlTab;
        $str .= 'public function getPrimaryKeyValue(){' . $endlTab2 . 'return $this->' . $this->getPrimaryKeyName($name) . ';' . $endlTab . '}' . $endl .$endlTab;
        $str .= '//Getters and setters' . $endlTab;
        $str .= $strGtrStr;
        $str .= $endl . '}';
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

    public function getPrimaryKeyName($tableName)
    {
        $req = 'SHOW INDEXES FROM ' . $tableName . ' WHERE Key_name=\'PRIMARY\';';
        $this->pdo->setReq($req);
        return $this->pdo->query()->tab()[0]->Column_name;
    }

    public function fromMySqlToPhpTypes($type)
    {
        $type = strtolower($type);
        if(strpos($type, 'char') !== false
            || strpos($type, 'text') !== false
            || strpos($type, 'blob') !== false
            || strpos($type, 'date') !== false
            || strpos($type, 'time') !== false
            || strpos($type, 'enum') !== false){
            return 'String';
        }
        elseif(strpos($type, 'int') !== false)
            return 'int';
        elseif(strpos($type, 'float') !== false)
            return 'float';
        elseif(strpos($type, 'double') !== false)
            return 'double';
        else
            return 'mixed';
    }

    public function getDefaultValueFromType($type)
    {
        switch ($type){
            case 'String':
                return '\'\'';
            case 'int':
                return '0';
            case 'float':
                return '0.0';
            case 'double':
                return '0.0';
            case 'mixed':
                return '\'\'';
            default :
                return '\'\'';
        }
    }
}