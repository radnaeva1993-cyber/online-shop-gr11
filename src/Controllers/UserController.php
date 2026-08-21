<?php

namespace Controllers;

use Model\User;
use Request\EditProfileRequest;
use Request\LoginRequest;
use Request\RegistrateRequest;

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

    public function registrate(RegistrateRequest $request)
    {
        $errors = $request->validate();

        if (empty($errors)) {

            $email = $request->getEmail();

            $user = $this->userModel->getByEmail($email);
            if ($user !== null) {
                $errors['email'] = "Пользователь с таким email уже существует";
            } else {

                $password = password_hash($request->getPassword(), PASSWORD_DEFAULT);
                $name = $request->getName();

                $this->userModel->insertIntoUser($name, $email, $password);
                echo "Регистрация прошла успешно";
            }

        }

        require_once '../Views/registration_form.php';
    }


    public function getLogin()
    {
        require_once '../Views/login_form.php';
    }

    public function login(LoginRequest $request)
    {
        $errors = $request->validate();

        if (empty($errors)) {

            $result = $this->authService->auth($request->getEmail(), $request->getPassword());

            if ($result) {
                header('Location: catalog');
                exit;

            } else {
                $errors['email'] = 'Пользователь или пароль неверный';

            }
        }

        // БАГ: require_once вьюхи был внутри if (empty($errors)), поэтому если валидация
        // (пустой email/пароль) сразу возвращала ошибки, метод завершался без единого
        // echo/require — пользователь видел пустую белую страницу вместо формы с ошибками.
        // Успешный логин по-прежнему завершается через header()+exit выше и сюда не доходит,
        // так что вынести require_once за пределы if можно безопасно.
        require_once '../Views/login_form.php';
    }

    // БАГ: раньше было два метода. profile() (GET /profile) правильно проверял сессию
    // и загружал $user. getProfile() был привязан к POST /profile, не проверял сессию
    // вообще и не задавал $user — если бы этот роут когда-нибудь сработал, вьюха profile.php
    // упала бы на $user->getName() (обращение к методу null). При этом форма на странице
    // профиля не имеет action и submit-кнопки, так что POST /profile был мёртвым и битым
    // кодом одновременно. Убрали лишний метод и роут (см. src/public/index.php),
    // оставили один рабочий обработчик.
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
        // БАГ: тут был вызов parent::logout(), а в BaseController такого метода нет —
        // при обращении к /logout это привело бы к фатальной ошибке "Call to undefined
        // method". К тому же роут /logout вообще не был зарегистрирован в index.php,
        // поэтому разлогиниться через интерфейс было невозможно (ссылка "Выйти" вела
        // на /login и ничего не делала). Теперь дёргаем logout() у самого authService —
        // он реализован в AuthSessionService/AuthCookieService и объявлен в AuthInterface,
        // роут /logout добавлен, а ссылка в catalog.php исправлена.
        $this->authService->logout();
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

    public function editProfile(EditProfileRequest $request)
    {

        if ($this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $user = $this->authService->getCurrentUser();

        $newName = $request->getName();
        $newEmail = $request->getEmail();
        $newPassword = $request->getPassword();

        if ($newName === '' || $newEmail === '') {
            header('Location: /edit-profile');
            exit;
        }

        $hashedPassword = null;
        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $this->userModel->updateNameEmailPasswordById($user->getId(), $newName, $newEmail, $hashedPassword);

        } else {
            $this->userModel->updateNameEmailById($user->getId(),$newName, $newEmail);
        }

        header('Location: /profile');
        exit;
    }
}
