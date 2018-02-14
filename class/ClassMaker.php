<?php

class ClassMaker
{
    private $tableArray = [];
    private $pdo = null;
    private $dir = '../../created';
    private static $tab = '    ';
    private static $tab2 = '        ';

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
            foreach ($this->classWritter($table, $columnTabs) as $line){
                fwrite($file, $line . PHP_EOL);
            }
            fclose($file);
            array_push($result, 'Création de la classe associée à la table ' . $table);
        }
        return json_encode($result);
    }

    //todo : ajouter la classe connexion et la classe DAO
    public function classWritter($tableName, $attributes)
    {
        //keys tab
        $ktb =[];
        //return tab
        $edt =[];
        $edt[] = '<?php';
        $edt[] = '//This file has been generated, DON\'T MODIFY IT, it will be overwritten by next update';
        $edt[] = '';
        $edt[] = 'class '. Tools::fromTableNameToClassName($tableName) . ' extends Dao';
        $edt[] = '{';
        $primaryKeyName = '';
        foreach ($attributes as $attribute) {
            if($attribute->Field == $this->getPrimaryKeyName($tableName)){
                $primaryKeyName = $attribute->Field;
                $primaryKeyType = $this->fromMySqlToPhpTypes($attribute->Type);
            }

            //incrementing attributes
            $edt[] = $this::$tab . '/**';
            $edt[] = $this::$tab . '* Sql type :  ' . $attribute->Type;
            $edt[] = $this::$tab . '* @var ' . $this->fromMySqlToPhpTypes($attribute->Type);
            $edt[] = $this::$tab . '**/';
            $edt[] = $this::$tab . 'protected $' . $attribute->Field . ';';

            //incrementing getters
            $ktb[] = '';
            $ktb[] = $this::$tab . '//Return '. $attribute->Field;
            $ktb[] = $this::$tab . 'public function get' . ucfirst($attribute->Field) . '(){';
            $ktb[] = $this::$tab2 . 'return $this->' . $attribute->Field . ';';
            $ktb[] = $this::$tab . '}';

            //incrementing setters
            $ktb[] = '';
            $ktb[] = $this::$tab . '//Setting '. $attribute->Field;
            $ktb[] = $this::$tab . 'public function set' . ucfirst($attribute->Field) . '($' . $attribute->Field . '){';
            $ktb[] = $this::$tab2 . ' $this->' . $attribute->Field . ' = $' . $attribute->Field . ';';
            $ktb[] = $this::$tab . '}';
        }

        //incrementing constructor
        $edt[] = '';
        $edt[] = $this::$tab . '//Constructor';
        $edt[] = $this::$tab . 'public function __construct(' . $this->getConstructorParams($attributes) . '){';
        $edt = $this->getConstructorValues($attributes, $edt);
        $edt[] = $this::$tab . '}';

        //incrementing functions
        $edt[] = '';
        $edt[] = '';
        $edt[] = $this::$tab . '/**********************';
        $edt[] = $this::$tab . '* DAO basic functions *';
        $edt[] = $this::$tab . '***********************/';
        $edt[] = $this::$tab . 'public function getTableName(){';
        $edt[] = $this::$tab2 . 'return \'' . $tableName . '\';';
        $edt[] = $this::$tab . '}';
        $edt[] = '';
        $edt[] = $this::$tab . 'public function getPrimaryKeyName(){';
        $edt[] = $this::$tab2 . 'return \'' . $this->getPrimaryKeyName($tableName) . '\';';
        $edt[] = $this::$tab . '}';
        $edt[] = '';
        $edt[] = $this::$tab . 'public function getPrimaryKeyValue(){';
        $edt[] = $this::$tab2 . 'return $this->' . $this->getPrimaryKeyName($tableName) . ';';
        $edt[] = $this::$tab . '}';
        $edt[] = '';
        $edt[] = '';
        $edt[] = $this::$tab . '/**********************';
        $edt[] = $this::$tab . '* Getters and setters *';
        $edt[] = $this::$tab . '***********************/';

        $ret = array_merge($edt, $ktb);
        $ret[] = '}';

        return $ret;
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

    public function fromMysqlToPdoType($type)
    {
        $type = strtolower($type);
        if(strpos($type, 'char') !== false
            || strpos($type, 'text') !== false
            || strpos($type, 'blob') !== false
            || strpos($type, 'date') !== false
            || strpos($type, 'time') !== false
            || strpos($type, 'enum') !== false){
            return 'PARAM_STR';
        }
        elseif(strpos($type, 'int') !== false)
            return 'PARAM_INT';
        elseif(strpos($type, 'float') !== false)
            return 'PARAM_INT';
        elseif(strpos($type, 'double') !== false)
            return 'PARAM_INT';
        else
            return 'PARAM_NULL';
    }

    public function getConstructorParams($attributes)
    {
        $surcharge = '';
        foreach ($attributes as $key => $attribute){
            $type = $this->fromMySqlToPhpTypes($attribute->Type);
            if($key !== 0){
                $surcharge .= ', ';
            }
            $surcharge .= '$' . $attribute->Field . ' = ' . $this->getDefaultValueFromType($type);
        }
        return $surcharge;
    }

    public function getConstructorValues($attributes, $tab)
    {
        foreach ($attributes as $key => $attribute){
            $tab[] = $this::$tab2 . '$this->' . $attribute->Field . ' = $' .$attribute->Field . ';';
        }
        return $tab;
    }
}