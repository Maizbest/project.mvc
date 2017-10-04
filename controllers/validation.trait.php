<?php
namespace App;

trait Validation {

    private function validateLogin($username) {
        //проверка логина
        if ( !$username ) {
            return 'Введите логин';
        } elseif ( !preg_match('/^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/', $username) ) {
            return 'Неверный логин';
        } 
        return false;
    }

    private function validatePassword($pass) {
        //проверка пароля
        if ( !$pass  ) {
            return 'Введите пароль';
        } elseif ( preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,25}$/', $pass) === false ) {
            return 'Неверный пароль';
        } 
        return false;
    }
            
}