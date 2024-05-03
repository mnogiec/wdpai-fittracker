<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function register()
    {
        if ($this->isGet()) {
            return $this->render('register', ["title" => "Register"]);
        }

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repeatedPassword = $_POST['repeatedPassword'];

        if ($password !== $repeatedPassword) {
            $this->render('register', ['errorMessage' => 'Passwords are not the same!']);
            return;
        }


        try {
            $this->userRepository->createUser([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'unique_email') !== false) {
                $this->render('register', ['errorMessage' => 'User with this email already exists!']);
            } else {
                $this->render('register', ['errorMessage' => 'Something went wrong! Try later!']);
            }
            return;
        }


        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }

    public function login()
    {
        if ($this->isGet()) {
            return $this->render('login');
        }

        //TODO check if user is authenticated
        $email = $_POST['email'];
        $password = $_POST['password'];

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
    }
}