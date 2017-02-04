<?PHP

namespace daos\mysql;

/**
 * MySQL specific statements
 *
 * @package    daos
 * @copyright  Copyright (c) Tobias Zeising (http://www.aditu.de)
 * @license    GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @author     Alexandre Rossi <alexandre.rossi@gmail.com>
 */
class Statements{

   /**
    * wrap insert statement to return id
    *
    * @param sql statement
    * @param sql params
    * @return id after insert
    */
    public static function insert($query, $params) {
        \F3::get('db')->exec($query, $params);
        $res = \F3::get('db')->exec('SELECT LAST_INSERT_ID() as lastid');
        return $res[0]['lastid'];
    }


   /**
    * null first for order by clause
    *
    * @param column to concat
    * @param order
    * @return full statement
    */
    public static function nullFirst($column, $order) {
        return "$column $order";
    }


   /**
    * sum statement for boolean columns
    *
    * @param boolean column to concat
    * @return full statement
    */
    public static function sumBool($column) {
        return "SUM($column)";
    }


   /**
    * bool true statement
    *
    * @param column to check for truth
    * @return full statement
    */
    public static function isTrue($column) {
        return "$column=1";
    }


   /**
    * bool false statement
    *
    * @param column to check for false
    * @return full statement
    */
    public static function isFalse($column) {
        return "$column=0";
    }


   /**
    * check if CSV column matches a value.
    *
    * @param CSV column to check
    * @param value to search in CSV column
    * @return full statement
    */
    public static function csvRowMatches($column, $value) {
        if( $value[0] == ':' )
            $value = "_utf8mb4 $value";

        return "CONCAT(',', $column, ',') LIKE CONCAT('%,', $value, ',%') COLLATE utf8mb4_general_ci";
    }

   /**
     * check column against int list.
     *
     * @param int column to check
     * @param array of string or int values to match column against
     * @return full statement
     */
    public static function intRowMatches($column, $ints) {
        // checks types
        if( !is_array($ints) && sizeof($ints) < 1 ) return null;
        $all_ints = array();
        foreach( $ints as $ints_str ) {
            $i = (int)$ints_str;
            if( $i > 0 ) $all_ints[] = $i;
        }

        if( sizeof($all_ints) > 0 ) {
            $comma_ints = implode(',', $all_ints);
            return $column." IN ($comma_ints)";
        }

        return null;
    }
}
