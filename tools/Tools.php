<?php
/**
 * This class is a gathering of all useful tools of the website
 */

class Tools
{
    /**
     * @param string $param
     * @param string $type
     * this function accepts as "type" 'string', 'int', 'double' and 'boolean'
     * @return mixed
     */
    public static function getPost($param, $type = 'string'){
        switch ($type) {
            case 'string' :
                return isset($_POST[$param]) ? strval($_POST[$param]) : '';
                break;
            case 'int' :
                return isset($_POST[$param]) ? intval($_POST[$param]) : 0;
                break;
            case 'double' :
                return isset($_POST[$param]) ? doubleval($_POST[$param]) : 0.0;
                break;
            case 'boolean' :
                return isset($_POST[$param]) && ($_POST[$param] == 'true' || $_POST[$param] == 1) ? true : false;
                break;
            default :
                return isset($_POST[$param]) ? strval($_POST[$param]) : '';
        }
    }

        /**
         * @param string $param
         * @param string $type
         * this function accepts as "type" 'string', 'int', 'double' and 'boolean'
         * @return mixed
         */
        public static function getGet($param, $type = 'string')
        {
            switch ($type) {
                case 'string' :
                    return isset($_GET[$param]) ? strval($_GET[$param]) : '';
                    break;
                case 'int' :
                    return isset($_GET[$param]) ? intval($_GET[$param]) : 0;
                    break;
                case 'double' :
                    return isset($_GET[$param]) ? doubleval($_GET[$param]) : 0.0;
                    break;
                case 'boolean' :
                    return isset($_GET[$param]) && ($_GET[$param] == 'true' || $_GET[$param] == 1) ? true : false;
                    break;
                default :
                    return isset($_GET[$param]) ? strval($_GET[$param]) : '';
        }
    }

    /**
     * @param string $tableName
     * @return string
     * Return the class name from a table name
     */
    public static function fromTableNameToClassName($tableName){

        return str_replace(' ', '', (ucwords(str_replace('_', ' ', $tableName))));
    }

    /**
     * @param string $classname
     * @return string
     * Return the class name from a table name
     */
    public static function fromClassNameToTableName($classname){
        $firstLetter = strtolower(substr($classname, 0, 1));
        $classname[0] = $firstLetter;
        return preg_replace('[A-Z}', '_$1' , $classname);
    }
}