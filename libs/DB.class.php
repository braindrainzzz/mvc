<?php
/**
 * ����� ��� ������ � ��
 *
 * �������������:
 *  DB::connect('localhost', 'root', '', '<��� ��>'); - ������������ � ��
 *  DB::exeQuery('Select * From table;'); - ��������� ������ �� �������
 *  $data = DB::getResult('array'); - ������� ��������� ������� � ����������
 *  DB::disconect(); - ��������� ����������
 *
 * DB::insert('table_name', array('fields1'=>'values1'));
 * DB::update('table_name', array('fields1'=>'values1'), array('fields1'=>'condition1'));
 * DB::delete('table_name', array('fields'=>'condition'));
 * DB::select('table_name', array(fields), [array('fields1'=>'condition1')]);
 *
 * @author Sultanov Damir <damir.s94777@gmail.com>
 */
class DB
{
    /**
     * @param string c����� �� ����������� � ���� ������
     */
    private static $link;

    /**
     * @param boolean ��������� �������
     */
    private static $result;

    /**
     * @param object ������ �� ������
     */
    private static $instance;

    /**
     * @param string ������
     */
    private static $data = array();

    /**
     * @param string ����� ������������ SQL-�������
     */
    private static $query;

    /**
     * @param string ��� �������
     */
    private static $table;

    /**
     * �������� �������(��������)
     * @return object
     */
    public static function getInstance()
    {
        return (null !== self::$instance) ? self::$instance : (self::$instance = new self());
    }

    /**
     * ����������� � ��
     * @param $host string ��� �������
     * @param $user string ��� ������������
     * @param $password string ������
     * @param $db_name string ��� ���� ������
     * @throws Exception
     */
    public static function connect($host, $user, $password, $db_name)
    {
        self::$link = new mysqli($host, $user, $password, $db_name);
        if (self::$link->connect_error) {
            throw new Exception(get_class() . ': Could not connect: ' . self::$link->connect_error);
        }
        return self::$link;
    }

    /**
     * ���������� �� ��
     */
    public static function disconect()
    {
        self::$link->close();
    }

    /**
     * ������������ ���������
     * @param string $charset ���������
     * @throws Exception
     */
    public static function setCharSet($charset)
    {
        if (!self::$link->set_charset($charset)) {
            throw new Exception('Error loading character set : ' . $charset);
        }
    }

    /**
     * ����� ���������
     * @return string ���������� ��� ������������� ���������
     */
    public static function getCharSet()
    {
        return self::$link->character_set_name();
    }

    /**
     * ������� ��������� �������
     * @param string $type ...
     * @throws Exception
     * @return array/object ���������� ��������� ���������� �������
     */
    public static function getResult($type = 'array')
    {
        switch ($type) {
            case 'array':
                while ($row = self::$result->fetch_assoc()) {
                    self::$data[] = $row;
                }
                break;
            case 'object':
                while ($obj = self::$result->fetch_object()) {
                    self::$data[] = $obj;
                }
                break;
            default:
                throw new Exception('Undefined argument: ' . $type);
        }
        return self::$data;
    }

    /**
     * ��������� ������
     * @param $query string SQL - ������
     * @throws Exception
     * @return string
     */
    public static function exeQuery($query)
    {
        self::$result = self::$link->query($query);
        if (self::$link->errno) {
            throw new Exception('Error executing the query: ' . self::$link->error);
        }
        return self::$query = $query;
    }

    /**
     * ����� ������ ���������� �������
     * @return string ���������� ���������� SQL-�������
     */
    public static function getLastQuery()
    {
        return self::$query;
    }

    /**
     * ����� ���������� �� SQL-�������
     * @return array ���������� ���������� �� SQL-�������
     */
    public static function setQueryInfo()
    {
        $info = array(
            'affected_rows' => self::$result->affected_rows,
            'insert_id' => self::$result->insert_id,
            'num_rows' => self::$result->num_rows,
            'field_count' => self::$result->field_count,
            'sqlstate' => self::$result->sqlstate,
        );
        return $info;
    }

    /**
     * �������� ����� ����� � �������������� �������
     *
     * @return boolean true or false
     */
    public static function getNumRows()
    {
        return (self::$result->num_rows) ? true : false;
    }

    /**
     * ���������� ����� insert
     * ������������� ��������� �������:
     * 1 �������� - ������ � �������
     *
     * DB::insert(array('fields1'=>'values1'));
     * @param $fields array ������
     */
    public static function insert($fields)
    {
        $fields_array = DB::setArrayParameters('fields', $fields);
        $values_array = DB::setArrayParameters('values', $fields);
        $sql = "INSERT INTO " . self::getTable() . " ($fields_array) VALUES ($values_array);";
        DB::ExeQuery($sql);
    }

    /**
     * ���������� ����� update
     * ������������� ��������� �������:
     * 1 �������� - ������: �������� ���� = ��������
     * 2 �������� - �������
     *
     * DB::update(array('fields1'=>'values1'), array('fields1'=>'condition1'));
     * @param $set array ����������� ���� �� ��������� ����������
     * @param $condition array ������� �� �������� ����� ��������� ����������
     */
    public static function update($set, $condition)
    {
        $set_array = DB::setArrayParameters('set', $set);
        $conditions_array = DB::setArrayParameters('where', $condition);
        $sql = "UPDATE " . self::getTable() . " SET $set_array WHERE ($conditions_array) ;";
        DB::ExeQuery($sql);
    }

    /**
     * ���������� ����� select
     * ������������� ��������� �������:
     * 1 �������� - ������������ ����
     * 2 �������� - �������
     * 3 �������� - �������
     *
     * DB::select(array(fields), [array('fields1'=>'condition1', 'fields2'=>'condition')], ['opedand']);
     * @param $fields string ����, ������� ����� ������������
     * @param $conditions array ������� �� �������� ����� ��������� �������
     * @param $operand string ���������� ��������, ����������� 2 ��� ����� �������
     * @return string
     */
    public static function select($fields, $conditions = null, $operand = null)
    {
        is_array($fields) ? $fields_array = DB::setArrayParameters('fields', $fields) : $fields_array = $fields;
        if ($conditions != null) {
            if ($operand == null) {
                $conditions_array = "WHERE (" . DB::setArrayParameters('where', $conditions) . ");";
            } else {
                $conditions_array = "WHERE (" . DB::setArrayParameters('where', $conditions, $operand) . ");";
            }
        } else {
            $conditions_array = null;
        }

        $sql = "SELECT $fields_array FROM " . self::getTable() . " $conditions_array ;";
        return DB::ExeQuery($sql);
    }

    /**
     * ���������� ����� delete
     * ������������� ��������� �������:
     * 1 �������� - �������
     *
     * DB::delete(array('fields'=>'condition'));
     * @param $conditions array ������� �� �������� ����� ��������� �������
     */
    public static function delete($conditions)
    {
        $conditions_array = DB::setArrayParameters('where', $conditions);
        $sql = "DELETE FROM " . self::getTable() . " WHERE ($conditions_array)";
        DB::ExeQuery($sql);
    }

    /**
     * ��������������� ������ � ������
     * @param $type string
     * @param $data array ������ � �������
     * @param $operands ���������� ��������
     * @return string ���������� ��������������� ������ �� �������
     * @throws Exception
     */
    public static function setArrayParameters($type, $data, $operand = null)
    {
        $lenght = 2;
        $input_string = '';
        foreach ($data as $key => $value) {
            switch ($type) {
                case 'set':
                    $input_string .= ", `$key` = '$value'";
                    break;
                case 'where':
                    if ($operand == null) {
                        $input_string .= ", `$key` = '$value'";
                    } else {
                        $lenght = strlen(trim($operand)) + 1;
                        $input_string .= " $operand `$key` = '$value'";
                    }
                    break;
                case 'fields':
                    $input_string .= ", `$key`";
                    break;
                case 'values':
                    $input_string .= ", '$value'";
                    break;
                default:
                    throw new Exception('Type is not correct');
            }
        }
        return substr($input_string, $lenght);

    }

    /**
     * �������� ��� �������������� �������
     * @return string ���������� ��� �������
     */
    public static function getTable()
    {
        return self::$table;
    }

    /**
     * ������������� ��� ������������ �������
     * @param $table string ��� �������
     */
    public static function setTable($table)
    {
        self::$table = $table;
    }

}