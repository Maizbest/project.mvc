<?php
namespace App;

require_once __DIR__."/../models/user.model.php";
require_once __DIR__."/../controllers/checkUser.trait.php";

class Users
{
    use CheckUser;

    protected $isAjax;    

    function __construct() {
        $this -> checkCookie();
        $this -> isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function index($params = null)
    {
        $orderBy = $params[0];
        $keyword = $params[1];

        $users_model = new User(); //создаем объект
        $users = $users_model->getAllUsers($orderBy, $keyword); //получаем всех юзеров

        if (!$users) throw new \Exception("Wrong parameters");

        $view = new View();
        $data['users'] = $users;
        $view->render('users/index', $data);
        $view->render("scripts");
    }

    public function show($params)
    {   
        $id = $params[0];

        if ($params[1]) throw new \Exception("Wrong parameters");

        if($this -> isAjax) { 

            $file = empty($_FILES['image']) ? null : $_FILES['image'];//грузим картинку
            $result = [
                'errors' => array(),
                'success' => false
            ];

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
                    $user = $users_model->getUserById($id);

                    if ($user['photo']) {
                        unlink(__DIR__.'/../uploads/' . $user['photo']);
                    };    

                    $users_model->updateUserPhoto($id, $new_name);
                } 
            }

            if (empty($result['errors'])) $result['success'] = true;

            echo json_encode($result);
            return;
        }

        if ($id === $_SESSION['user_id']) header('Location: /users/profile');
        $users_model = new User(); //создаем объект
        $user = $users_model->getUserById($id); //получаем юзера

        if (!$user) throw new \Exception("Wrong parameters");

        $view = new View();
        $data['user'] = $user;
        $view->render('users/show', $data);
        $view->render("scripts");
    }

    public function profile()
    {
        if($this -> isAjax) {
            
            $inputs = json_decode($_POST['inputs'], assoc); //валидация данных формы

            $fields = [
                'name' => FILTER_SANITIZE_STRING,
                'age' => FILTER_SANITIZE_NUMBER_INT,
                'desc' => FILTER_SANITIZE_STRING
            ];

            if ($inputs = filter_var_array($inputs, $fields)) {
                $users_model = new User(); //создаем объект
                $user = $users_model->updateProfile($_SESSION['user_id'], $inputs['name'], $inputs['age'], $inputs['desc']);  
            }   

            $file = empty($_FILES['image']) ? null : $_FILES['image'];//грузим картинку
            $result = [
                'errors' => array(),
                'success' => false
            ];

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
                    $user = $users_model->getUserById($_SESSION['user_id']);

                    if ($user['photo']) {
                        unlink(__DIR__.'/../uploads/' . $user['photo']);
                    };    

                    $users_model->updateUserPhoto($_SESSION['user_id'], $new_name);
                } 
            }

            if (empty($result['errors'])) $result['success'] = true;

            echo json_encode($result);
            return;
        }

        $users_model = new User(); //создаем объект
        $user = $users_model->getUserById($_SESSION['user_id']); //получаем юзера

        $view = new View();
        $data['user'] = $user;
        $view->render('users/profile', $data);
        $view->render("scripts");
    }

}