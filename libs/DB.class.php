<?php
/**
 * Класс для работы с БД
 *
 * Использование:
 *  DB::connect('localhost', 'root', '', '<имя БД>'); - подключаемся к БД
 *  DB::exeQuery('Select * From table;'); - выполняем запрос на выборку
 *  $data = DB::getResult('array'); - заносим результат запроса в переменную
 *  DB::disconect(); - закрываем соединение
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
     * @param string cсылка на подключение к базе данных
     */
    private static $link;

    /**
     * @param boolean результат запроса
     */
    private static $result;

    /**
     * @param object ссылка на объект
     */
    private static $instance;

    /**
     * @param string данные
     */
    private static $data = array();

    /**
     * @param string текст выполненного SQL-запроса
     */
    private static $query;

    /**
     * @param string имя таблицы
     */
    private static $table;

    /**
     * Создание объекта(одиночка)
     * @return object
     */
    public static function getInstance()
    {
        return (null !== self::$instance) ? self::$instance : (self::$instance = new self());
    }

    /**
     * Подключение к БД
     * @param $host string имя сервера
     * @param $user string имя пользователь
     * @param $password string пароль
     * @param $db_name string имя базы данных
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
     * Отключение от БД
     */
    public static function disconect()
    {
        self::$link->close();
    }

    /**
     * Установление кодировки
     * @param string $charset кодировка
     * @throws Exception
     */
    public static function setCharSet($charset)
    {
        if (!self::$link->set_charset($charset)) {
            throw new Exception('Error loading character set : ' . $charset);
        }
    }

    /**
     * Вывод кодировки
     * @return string возвращает имя установленной кодировки
     */
    public static function getCharSet()
    {
        return self::$link->character_set_name();
    }

    /**
     * Выводит результат выборки
     * @param string $type ...
     * @throws Exception
     * @return array/object возвращает результат выполнения выборки
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
     * Выполняет запрос
     * @param $query string SQL - запрос
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
     * Вывод текста последнего запроса
     * @return string возвращает содержимое SQL-запроса
     */
    public static function getLastQuery()
    {
        return self::$query;
    }

    /**
     * Вывод информации по SQL-запросу
     * @return array возвращает информацию по SQL-запросу
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
     * Получает число рядов в результирующей выборке
     *
     * @return boolean true or false
     */
    public static function getNumRows()
    {
        return (self::$result->num_rows) ? true : false;
    }

    /**
     * Коробочный метод insert
     * Используеться следующим образом:
     * 1 параметр - массив с данными
     *
     * DB::insert(array('fields1'=>'values1'));
     * @param $fields array данные
     */
    public static function insert($fields)
    {
        $fields_array = DB::setArrayParameters('fields', $fields);
        $values_array = DB::setArrayParameters('values', $fields);
        $sql = "INSERT INTO " . self::getTable() . " ($fields_array) VALUES ($values_array);";
        DB::ExeQuery($sql);
    }

    /**
     * Коробочный метод update
     * Используеться следующим образом:
     * 1 параметр - данные: название поля = значение
     * 2 параметр - условие
     *
     * DB::update(array('fields1'=>'values1'), array('fields1'=>'condition1'));
     * @param $set array обновляемое поле со значением обновления
     * @param $condition array условие по которому будет проходить обновление
     */
    public static function update($set, $condition)
    {
        $set_array = DB::setArrayParameters('set', $set);
        $conditions_array = DB::setArrayParameters('where', $condition);
        $sql = "UPDATE " . self::getTable() . " SET $set_array WHERE ($conditions_array) ;";
        DB::ExeQuery($sql);
    }

    /**
     * Коробочный метод select
     * Используеться следующим образом:
     * 1 параметр - отображаемые поля
     * 2 параметр - условие
     * 3 параметр - операнд
     *
     * DB::select(array(fields), [array('fields1'=>'condition1', 'fields2'=>'condition')], ['opedand']);
     * @param $fields string поля, которые будут отображаться
     * @param $conditions array условие по которому будет проходить выборка
     * @param $operand string логический оператор, соединяющий 2 или более условий
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
     * Коробочный метод delete
     * Используеться следующим образом:
     * 1 параметр - условие
     *
     * DB::delete(array('fields'=>'condition'));
     * @param $conditions array условие по которому будет проходить выборка
     */
    public static function delete($conditions)
    {
        $conditions_array = DB::setArrayParameters('where', $conditions);
        $sql = "DELETE FROM " . self::getTable() . " WHERE ($conditions_array)";
        DB::ExeQuery($sql);
    }

    /**
     * Преобразовывает массив в строку
     * @param $type string
     * @param $data array массив с данными
     * @param $operands логический оператор
     * @return string возвращает преобразованную строку из массива
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
     * Получает имя использованной таблицы
     * @return string возвращает имя таблицы
     */
    public static function getTable()
    {
        return self::$table;
    }

    /**
     * Устанавливает имя используемой таблицы
     * @param $table string имя таблицы
     */
    public static function setTable($table)
    {
        self::$table = $table;
    }

}