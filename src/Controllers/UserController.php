<?php

namespace Controllers;

use Model\User;

class UserController extends BaseController
{

    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function getRegistrate()
    {

        require_once '../Views/registration_form.php';
    }

    public function registrate()
    {
        $data = $_POST;
        $errors = $this->registrationValidate($data);

        if (empty($errors)) {

            $email = $data['email'];

            $user = $this->userModel->getByEmail($email);
            if ($user !== null) {
                $errors['email'] = "Пользователь с таким email уже существует";
            } else {

                $password = password_hash($data['psw'], PASSWORD_DEFAULT);
                $name = $data['name'];

                $this->userModel->insertIntoUser($name, $email, $password);
                echo "Регистрация прошла успешно";
            }

        }

        require_once '../Views/registration_form.php';
    }

    private function registrationValidate($data)
    {

        $errors = [];

        if (isset($data['name'])) {
            $name = $data['name'];

            if (strlen($name) < 2) {
                $errors['name'] = 'Слишком короткое имя';
            }
        } else {
            $errors['name'] = "Имя должно быть заполнено";
        }

        if (isset($data['email'])) {
            $email = $data['email'];

            if (strlen($email) < 2) {
                $errors['email'] = 'email должен быть больше 2 символов';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors['email'] = 'email некорректный';
            }
        } else {
            $errors['email'] = "email должен быть заполнен";
        }

        if (isset($data['psw'])) {
            $password = $data['psw'];

            if (strlen($password) < 4) {
                $errors['psw'] = 'Пароль слишком короткий.Придумайте новый пароль';
            }
        } else {
            $errors['psw'] = 'Пароль должен быть заполнен';
        }

        if (isset($data['psw-repeat'])) {
            $passwordRepeat = $data['psw-repeat'];

            if ($password != $passwordRepeat) {
                $errors['psw-repeat'] = 'Пароли не совпадают';
            }
        } else {
            $errors['psw-repeat'] = 'Пароль должен быть заполнен';
        }
        return $errors;
    }

    public function getLogin()
    {
        require_once '../Views/login_form.php';
    }

    public function login()
    {
        $errors = $this->loginValidate($_POST);

        if (empty($errors)) {

            $result = $this->authService->auth($_POST["email"], $_POST["password"]);

            if ($result) {
                header('Location: catalog');
                exit;

            } else {
                $errors['email'] = 'Пользователь или пароль неверный';

            }
            require_once '../Views/login_form.php';
        }
    }

    private function loginValidate($data)
    {
        $errors = [];

        if (empty($data['email'])) {

            $errors['email'] = "email должен быть заполнен";
        }

        if (empty($data['password'])) {

            $errors['password'] = 'Пароль должен быть заполнен';
        }
        return $errors;

    }

    public function getProfile()
    {
        require_once '../Views/profile.php';
    }

    public function profile()
    {

        if ($this->authService->checkSession()) {

            $user = $this->authService->getCurrentUser();

            require_once '../Views/profile.php';

        } else {
            header('Location: /login');
        }
    }


    public function logout()
    {
        parent::logout();
        header('Location: /login');
        exit;
    }
    public function getEditProfile()
    {

        if ($this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $user = $this->authService->getCurrentUser();

        $result = $this->userModel->getNameEmailById($user->getId());

        if ($result === null) {
            header('Location: /login');
            exit;
        }
        require_once '../Views/edit_profile_form.php';
    }

    public function editProfile()
    {

        if ($this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $newName = $_POST['name'];
        $newEmail = $_POST['email'];
        $newPassword = $_POST['password'];

        if ($newName === '' || $newEmail === '') {
            header('Location: /edit-profile');
            exit;
        }

//        $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'dolgor', '12345678');

        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $this->userModel->updateNameEmailPasswordById($newName, $newEmail, $hashedPassword);

        } else {
            $this->userModel->updateNameEmailById($newName, $newEmail);
        }

        header('Location: /profile');
        exit;
    }
}
