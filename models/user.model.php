<?php

namespace App;

class User
{  
    public function getAllUsers($orderBy = null, $keyword = null)
    {
        $keyword = filter_var($keyword, FILTER_SANITIZE_STRING);
        $orderBy = filter_var($orderBy, FILTER_SANITIZE_STRING);

        $query = 'SELECT id, name, age, photo FROM users'; 

        if ($orderBy && $keyword) {
            $query = "SELECT id, name, age, photo FROM users ORDER BY $orderBy $keyword";
        }
        return DB::run($query) -> fetchAll();
    }

    public function getUserById($id)
    {
        return DB::run('SELECT * FROM users WHERE id=?', [$id]) -> fetch();
    }

    public function getUserByUsername($username)
    {
        return DB::run('SELECT * FROM users WHERE username=?', [$username]) -> fetch();
    }

    public function createNewUser($username,$pass)
    {
        DB::run('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $pass]);
        return DB::instance()->lastInsertId();
    }

    public function updateProfile($id, $name, $age, $desc) 
    {
        $userInfo = $this -> getUserById($id);

        $name = empty($name) ? $userInfo['name'] : $name;
        $age = empty($age) ? $userInfo['age'] : $age;
        $desc = empty($desc) ? $userInfo['description'] : $desc;

        DB::run("UPDATE users SET name = ?, age = ?, description = ? WHERE id = ?",[$name,$age,$desc,$id]);
    }

    public function updateUserPhoto($id, $photo) {

        DB::run("UPDATE users SET photo = ? WHERE id = ?", [$photo, $id]);

    }

}