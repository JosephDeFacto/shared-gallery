<?php

namespace App\Model;
use App\Core\DB;
use PDO;

class User
{

    private $id;
    private $firstname;
    private $lastname;
    private $username;
    private $email;
    private $password;

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $data
     * @return bool
     */
    public function register($data)
    {
        $sql = "INSERT INTO user (firstname, lastname, username, email, password) VALUE (:firstname, :lastname, :username, :email, :password)";

        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);

        $stmt->execute();

        return true;
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @return mixed
     */
    public function login($username, $email, $password)
    {
        $sql = "SELECT id, username FROM user WHERE username = :username OR email = :email OR password = :password";
        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        $rows = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $rows['id'];
        $this->username = $rows['username'];

        return $this->id . $this->username;
    }

    public function changePassword($data)
    {
        $sql = "UPDATE user set password = :password WHERE email = :email";

        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);

        $stmt->execute();

        return true;
    }

    public function deleteAccount($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = DB::getInstance()->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        if ($stmt) {
            return true;
        }
        return false;

    }

}