<?php

namespace App;

require_once __DIR__."/../models/user.model.php";
require_once __DIR__."/../controllers/checkUser.trait.php";
require_once __DIR__."/../controllers/validation.trait.php";

class Main
{
    use CheckUser, Validation;

    protected $isAjax;    

    function __construct() {
        $this -> checkCookie();
        $this -> isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function index()
    {
        $view = new View();
        $view->render("index");
        
    }

    public function admin()
    {
        if($this -> isAjax) {

            $data = json_decode($_POST['inputs'], $assoc = true);

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
                $pass = password_hash($pass, PASSWORD_BCRYPT); //хеширование пароля    
                $user_id = $users_model -> createNewUser($username, $pass);//сохраняем нового пользователя в базу
            }

            //валидация данных доп информации
            $fields = [
                'name' => FILTER_SANITIZE_STRING,
                'age' => FILTER_SANITIZE_NUMBER_INT,
                'desc' => FILTER_SANITIZE_STRING
            ];

            $inputs = $data;

            if ($inputs = filter_var_array($inputs, $fields)) {
                $user = $users_model->updateProfile($user_id, $inputs['name'], $inputs['age'], $inputs['desc']);  
            }   

            $file = empty($_FILES['image']) ? null : $_FILES['image'];//грузим картинку

            if ($file) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $img_exts = ['jpeg', 'jpg','png', 'gif'];

                if ( !in_array($ext, $img_exts) ) {
                    array_push($result['errors'],'Это не изображение.');
                } elseif ( $file['error'] <> 0 ) {
                    array_push($result['errors'], 'Ошибка: ' . $file['error']);
                } elseif ($file['size'] > 5242880) {//>5Mb 
                    array_push($result['errors'],'Файл лишком большой');
                }

                $upl_dir = __DIR__.'/../uploads';
                $new_name = uniqid() . '-' . time() .'.'. $ext;
                $full_path = "$upl_dir/$new_name";

                if (move_uploaded_file($file['tmp_name'], $full_path)) {

                    $users_model = new User(); //создаем объект
                    $user = $users_model->getUserById($user_id);

                    if ($user['photo']) {
                        unlink(__DIR__.'/../uploads/' . $user['photo']);
                    };    

                    $users_model->updateUserPhoto($user_id, $new_name);
                }
            }
            if (empty($result['errors'])) $result['success'] = true;

            echo json_encode($result);
            return;
        }   
        $view = new View();
        $view->render("admin");
        $view->render("scripts");
        
    }

}