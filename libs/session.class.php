<?php
/**
 * Класс работы с сессиями
 *
 * Использование
 *  session::getInstance()->start();
 *  dump(session::getInstance()->get('qwer'));
 *  session::getInstance()->set('qwer', 'qwer');
 *          1 параметр $key - наименование сессии
 *          2 параметр $value - значени сессии
 *
 */
class Session
{
    /**
     * @param $instance переменная предназначеная для
     * хранения экземпляра класса Session
     */
    protected static $instance;

    /**
     * @param $session_start boolean идентификатор работы сессии
     */
    private $session_start = FALSE;


    private function __construct()
    {

    }

    public static function getInstance()
    {
        return (null !== self::$instance) ? self::$instance : (self::$instance = new self());
    }


    /**
     * функция старта сессии
     * Возращает true если сессия стартовала успешно
     */
    public function start()
    {
        $this->session_start = true;
        if (!$this->session_start)
            throw new Exception('Error session start');
        else
            return true;
    }


    /**
     * Удаляет сессию
     * @param $key string имя сессии
     */
    public function destroy($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Возвращает значение элемента $key
     * @param $key string имя сессии
     * @return bool
     */
    public function get($key)
    {
        return (!empty($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    /**
     * Устанавливаем элементу $key значение $value
     * @param $key string имя сессии
     * @param $value string значение сессии
     */
    public function set($key, $value)
    {
        session_start();
        $this->start();
        $_SESSION[$key] = $value;
    }
}

Session::getInstance()->start();
Session::getInstance()->get('qwer');
Session::getInstance()->set('qwer', 'qwer');