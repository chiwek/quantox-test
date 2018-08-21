<?php
namespace App\Models;


class UserRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Save or update the user details
     * @param User $user
     */
    public function saveUser(User $user) {
        $values = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => password_hash($user->password, PASSWORD_BCRYPT)
        ];

        if ($user->id < 1) {
            $this->db->query("INSERT INTO users",$values);
        } else {
            $this->db->query("UPDATE users SET",$values);
        }
    }
    /**
     * @param $email
     * @return User|null
     */
    public function loadUserByEmail($email) {
        $result = $this->db->query("SELECT * FROM users where email='{$email}'")->fetchAll();
        $user = null;
        if (count($result) > 0) {
            $user = $this->setUserObjectFromArray($result[0]);
        }

        return $user;

    }

    /**
     * @param $email
     * @return User|null
     */
    public function loadUserByEmailPassword($email, $password) {
        $result = $this->db->query("SELECT * FROM users where email='{$email}' AND password = '{$password}'")->fetchAll();
        $user = null;
        if (count($result) > 0) {
            $user = $this->setUserObjectFromArray($result[0]);
        }

        return $user;

    }

    /**
     * @param $text
     * @return [User]
     */
    public function findUsers($text) {
        $results = $this->db->query("SELECT * FROM users where `email` LIKE '%{$text}%' OR `name` LIKE '%{$text}%'")->fetchAll();
        $users = [];
        foreach ($results as $result) {
            $users[] = $this->setUserObjectFromArray($result);
        }

        return $users;
    }

    /**
     * @param $dataArray
     * @return User
     */
    private function setUserObjectFromArray($dataArray) {
        $user = new User();
        foreach ($dataArray as $key=>$value) {
                $user->{$key}=$dataArray[$key];
        }
        return $user;
    }

}