<?php
namespace App;

require_once __DIR__."/../models/user.model.php";
require_once __DIR__."/../controllers/checkUser.trait.php";
require_once __DIR__."/../controllers/validation.trait.php";

class Reg {

    use CheckUser, Validation;

    public function index()
    {  
        $view = new View();
        //УСловие для АЯКС запроса
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data = json_decode($_POST['dataJson'], $assoc = true);
            $username = trim($data['username']);
            $pass = trim($data['pass']);
            $error;
            $result = [
                'errors' => array(),
                'success' => false
            ];

            //проверка логина и пароля
            if ($error = $this -> validateLogin($username)) {
                array_push($result['errors'], $error);
            } elseif ($error = $this -> validatePassword($pass)) {
                array_push($result['errors'], $error);
            } elseif ($pass !== trim($data['pass2'])) {
                array_push($result['errors'], 'Пароли не совпадают');
            }

            $users_model = new User(); //создаем объект
            $user = $users_model->getUserByUsername($username); //получаем юзера

            if ($user) {
                array_push($result['errors'], 'Имя пользователя занято');
            }

            if (empty($result['errors'])) {
                $result['success'] = true;

                $pass = password_hash($pass, PASSWORD_BCRYPT); //хеширование пароля    
                $user_id = $users_model -> createNewUser($username, $pass);//сохраняем пользователя в базу, возвращая его id

                session_start();
                $_SESSION["user_id"] = $user_id;
                setcookie('sid', session_id(), time() + 24*3600, '/',"",false,true);
            }

            echo json_encode($result);
            
        } else {
            if (isset($_COOKIE['sid'])||isset($_COOKIE['PHPSESSID'])) {
                header('Location: /');
            } else {
                $view->render("reg/index");
                $view->render("scripts");
            }            
        }        
    }
}