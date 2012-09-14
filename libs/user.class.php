<?php

class user
{

    /**
     * Конструктор класса user
     * Устанавливает текущую таблицу для записи
     */
    public function __construct()
    {
        DB::setTable("users");
    }

    private function __clone()
    {
    }

    /**
     * Регистрация пользователя
     * @param $username
     * @param $password
     * @param $email
     * @return void
     */
    public static function registration($username, $password, $email)
    {
        $password = md5($password);
        DB::insert(array("username" => $username, "password" => $password, "email" => $email)) ? true : false;
    }

    /*
    * Функция инициализируется при первом запуске модуля, создаёт таблицу и записывает
    * тестовый аккаунт admin / admin
    * Используется на главной странице modules/users/index.php
    */
    public static function createFirstUser()
    {
        DB::connect('localhost', 'root', '123', 'my');
        DB::setTable("users");
        DB::exeQuery("CREATE TABLE IF NOT EXISTS `users` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL UNIQUE,
                      `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                      `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL UNIQUE,
                      `active` tinyint(1) NOT NULL DEFAULT '1',
                      `role` int(11) DEFAULT '1',
                      PRIMARY KEY (`id`)
                    )ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
        DB::select("id", array("id" => 1));
        if (DB::getNumRows() != 1)
            DB::insert(array("username" => "admin", "password" => md5("admin"), "email" => "admin@mail.ru", "role" => 2));
    }

    /**
     * Функция входа в приложения
     * @param $username логин пользователя
     * @param $password пароль
     * @return bool
     */
    public static function login($username, $password)
    {
        $password = md5($password);
        DB::select("id", array("username" => $username, "password" => $password), "AND");
        if (DB::getNumRows() == 1) {
            Session::getInstance()->start();
            Session::getInstance()->set('id', user::getUsernameId($username));
            return true;
        } else
            return false;
    }

    /**
     * Возвращает id по имени пользователя
     * @param $username
     * @return string
     */
    public static function getUsernameId($username)
    {
        DB::select("id", array('username' => $username));
        foreach (DB::getResult() as $key => $value)
            foreach ($value as $k => $id)
                return ($id) ? $id : false;
    }

    /**
     * Выход из приложения
     */
    public static function logout()
    {
        $session = Session::getInstance()->destroy('id');
        return (!Session::getInstance()->destroy('id')) ? true : false;
    }

    /**
     * Проверяет пользователя на вход в приложение
     * @return boolean
     */
    public static function isLogin()
    {
        $session = Session::getInstance()->get('id');
        return ($session) ? true : false;
    }

    /**
     * Проверяет статус пользователя
     * @param $id integer результатирующая выборка по id
     * @return boolean результат выборки в boolean формате
     */
    public static function isActive($id)
    {
        DB::select("active", array('id' => $id));
        return (DB::getResult() === true) ? true : false;
    }

    /**
     * Проверяет на пустоту и существование данных
     * @param $data
     * @return bool
     */
    public static function is_valid($data)
    {
        $data = trim(htmlspecialchars(strip_tags($data, ENT_QUOTES)));
        return (isset($data) && !empty($data)) ? $data : false;
    }

    /**
     * Проверка роли пользователя
     * @param $id осуществляется проверка по pk
     * @return string роль пользователя
     */
    public static function checkRole($id)
    {
        $mysql = new Mysqli("localhost", "root", "123", "my");
        $role = $mysql->query("SELECT role FROM users WHERE id=$id");

        if (isset($role)) {
            while ($row = $role->fetch_row())
                $newRole = $row[0];

            switch ($newRole) {
                case 0:
                    $textRole = "banned";
                    break;
                case 1:
                    $textRole = "user";
                    break;
                case 2:
                    $textRole = "admin";
                    break;
                default:
                    $textRole = "unknown role";
            }
            return $textRole;
        }
    }

    /**
     * Создание пользователя
     * используеться коробочный метод insert
     * @param $username string имя пользователя
     * @param $password string пароль пользователя
     * @param $email string email пользователя
     */
    public static function create($username, $password, $email)
    {
        DB::insert(array('username' => $username, 'password' => $password, 'email' => $email));
    }

    /**
     * Обновление пользователя
     * @param $set
     * @param $where mixed условие по которому следует сделать update
     */
    public static function update($set, $where)
    {
        DB::update($set, $where);
    }

    /**
     * Удаление пользователя
     * @param $condition array необходимые условия выборки, по умолчанию null
     */
    public static function delete($condition)
    {
        DB::delete($condition);
    }

    /**
     * Возвращает информацию о пользователе
     * @param $fields string выбираемые поля
     * @param $condition array необходимые условия выборки, по умолчанию null
     * @return array результат выборки, доступен режим 'object'
     */
    public static function getInfo($fields, $condition = null)
    {
        DB::select($fields, $condition);
        return DB::getResult();
    }
}