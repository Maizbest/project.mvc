<?php 
namespace App;

require_once __DIR__."/../models/user.model.php";
require_once __DIR__."/../controllers/checkUser.trait.php";
require_once __DIR__."/../controllers/validation.trait.php";

class Auth {

    use CheckUser, Validation;

    public function index()
    {
        $view = new View();

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
            }            

            $users_model = new User(); //создаем объект
            $user = $users_model->getUserByUsername($username);//тянем пользователя по логину

            if (!$user || password_verify($pass, $user['password']) === false) {
                array_push($result['errors'], 'Неверный логин или пароль');
            }

            if (empty($result['errors'])) {
                $result['success'] = true;
            
                session_start();
                $_SESSION["user_id"] = $user['id'];            
                setcookie('sid', session_id(), time() + 24*3600, '/',"",false,true); 
            } 

            echo json_encode($result);

        } elseif (isset($_COOKIE['sid']) || isset($_COOKIE['PHPSESSID'])) {
            header('Location: /');
        } else {
            $view->render("auth/index");
            $view->render("scripts");
        } 
    }

    public function logout() {
        setcookie ("PHPSESSID","",time()-3600,"/");
        setcookie ("sid","",time()-3600,"/");
        header('Location: /');
    }

}