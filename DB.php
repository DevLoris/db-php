<?php
define("DB_USER", "root");
define("DB_DATABASE", "foo");
define("DB_HOST", "localhost");
define("DB_PASS", "");
define("DB_PORT", "3306");
/**
 * @author Loris Pinna http://lorispinna.com 
 * @date 03.12.2017
 * @version 1.0.0
 * A class for manipulate easily DB
 */
class DB
{
    /**
     * @author Loris Pinna
     * @return mysqli object which represents the connection to a MySQL Server.
     */
    static $connexion;

    function __construct() {
        self::$connexion = @mysqli_connect(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_DATABASE,
            DB_PORT
        );

        return self::$connexion;
    }


    /**
     * @author Loris Pinna
     * @param $table string "request"
     * @return array which represents the first SQL line returned by the request
     */
    static function first($request) {
        return self::$connexion->query($request)->fetch_assoc();
    }

    /**
     * @author Loris Pinna
     * @param $table string "request"
     * @return integer which represents count of row returned by the request
     */
    static function count($request) {
        return self::$connexion->query($request)->num_rows;
    }

    /**
     * @author Loris Pinna
     * @param $table string "request"
     * @return array of array which contain all SQL line  returned by the request
     */
    static function all($request, $originalKey = MYSQLI_ASSOC) {
        return self::$connexion->query($request)->fetch_all($originalKey);
    }

    /**
     * @author Loris Pinna
     * @param $table string "request"
     * @return integer which is in the case of insert the last insert id
     */
    static function exec($request) {
        self::$connexion->query($request);
        return self::$connexion->insert_id;
    }
    /**
     * @author Loris Pinna
     * @return string which is the last SQL error
     */
    static function error() {
        return self::$connexion->error;
    }

    /**
     * @author Loris Pinna
     * @return array of all tables in the database
     */
    static function getTables() {
        $tables = array();
        $all =  self::all("SHOW TABLES", MYSQLI_NUM);
        foreach ($all as $value)
            $tables[] = $value[0];
        return $tables;
    }

    /**
     * @author Loris Pinna
     * @param $table string "table name"
     * @return array of all columns of the given table
     */
    static function getColumns($table) {
        $tables = array();
        $all =  self::all("SHOW COLUMNS FROM $table", MYSQLI_NUM);
        foreach ($all as $value)
            $tables[] = $value[0];
        return $tables;
    }

    /**
     * @author Loris Pinna
     * @param $table string "table name"
     * @return array of all primarykeys of the given table
     */
    static function getPrimaryKeys($table) {
        $tables = array();
        $all =  self::all("SHOW index from $table where Key_name = 'PRIMARY'",  MYSQLI_ASSOC);
        foreach ($all as $value) {
            $tables[] = $value['Column_name'];
        }
        return $tables;
    }

    /**
     * @author Loris Pinna
     * @param $table string "table name"
     * @return string which is the autoincrement columns
     */
    static function getAutoIncrement($table) {
        $data =  self::first("show columns from $table where extra like '%auto_increment%'");
        return $data['Field'];
    }

}
