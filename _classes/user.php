<?php

class user
{
    private  $id;
    private $email;
    private  $firstName;
    private  $lastName;
    private $password;
    private $reset_token;
    private $reset_token_expires;
    public function __construct($id)
    {
        global $db;
        $result = $db->query("SELECT * FROM users WHERE id = '$id'");
        $user = $result->fetch_assoc();
        $this->id = $user['id'];
        $this->firstName = $user['firstname'];
        $this->lastName = $user['lastname'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->reset_token = $user['reset_token'];
        $this->reset_token_expires = $user['reset_token_expires'];
    }
    static public function getId($email)
    {
        global $db;
        $sql = "SELECT id FROM user WHERE email = '$email'";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        if ($row['id']) {
            return $row['id'];
        }
        return NULL;
    }


    public function getlastName()
    {
        return $this->lastName;
    }

    public function setlastName($lastName)
    {
        $this->lastName = $lastName;
    }
    public function getfirstName()
    {
        return $this->firstName;
    }

    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    static public function registre($firstName, $lastName, $password, $email)
    {
        global  $db;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (firstname, lastname, password, email) VALUES (?, ?, ?, ?)";
        $insert = $db->prepare($sql);
        $insert->bind_param("ssss", $firstName, $lastName, $hashedPassword, $email);
        $insert->execute();
    }

    static public function login($enteredPassword, $email)
    {
        global $db;
        $sql_code = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql_code);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];
            if (password_verify($enteredPassword, $hashedPassword)) {
                return true;
            } else {
                return false;
            }
        }
    }

    static public function logout()
    {
        session_destroy();
        header("index.php?page=login");
    }


    static  public  function  getAll()
    {
        global $db;
        $result = $db->query("SELECT * FROM  users");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
