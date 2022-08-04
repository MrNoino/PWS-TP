<?php

class user{

    private int $id;

    private string $name;

    private string $email;

    private string $gender_code;

    private string $birthdate;

    private string $photo;

    public function __construct(int $id = 0, string $name = "", string $email = "", string $gender_code = "", string $birthdate = "", string $photo = "") {

        $this->id = $id;

        $this->name = $name;

        $this->email = $email;

        $this->gender_code = $gender_code;

        $this->birthdate = $birthdate;

        $this->photo = $photo;
        
    }

    public function get_id(): int{

        return $this->id;

    }

    public function set_id($id){

        $this->id = $id;

    }

    public function get_name(): string{

        return $this->name;

    }

    public function set_name($name){

        $this->name = $name;

    }

    public function get_email(): string{

        return $this->email;

    }

    public function set_email($email){

        $this->email = $email;

    }

    public function get_gender_code(): string{

        return $this->gender_code;

    }

    public function set_gender_code($gender_code){

        $this->gender_code = $gender_code;

    }

    public function get_birthdate(): string{

        return $this->birthdate;

    }

    public function set_birthdate($birthdate){

        $this->birthdate = $birthdate;

    }

    public function get_photo(): string{

        return $this->photo;

    }

    public function set_photo($photo){

        $this->photo = $photo;

    }

}

?>