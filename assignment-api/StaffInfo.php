<?php
/**
* @Author: Michael Piccoli
* @Version: 1.0
* @Date: 10/12/2015
* @Description: This class represents the Staff Object that this project is based on.
*/
Class StaffInfo{
    //Global Variables
    private $id;
    private $forename;
    private $surname;
    private $location;
    private $email;
    private $phoneNumber;
    /**
    * @Description: Constructor with all parameters
    * @Param  $id  an unique identifier for the this object
    * @Param  $sur  the surname of the person
    * @Param  $fore the forename of the person
    * @Param  $loc  the location the person is registering from
    * @Param  $phone  the phone number of the subscriber
    * @Param  $em  the email address of the subscriber
    * @Return an object of type StaffInfo with all parameters
    */
    public static function createWithParams ($id, $sur, $fore, $loc, $phone, $em){
        $instance=new self();
        $instance->id=$id;
        $instance->forename=$fore;
        $instance->surname=$sur;
        $instance->location=$loc;
        $instance->phoneNumber=$phone;
        $instance->email=$em;
        return $instance;
    }
    /**
    * @Description: Constructor with two parameters
    * @Param  $surname  the username of the person
    * @Param  $forename  the forename of the person
    * @Return an object of type StaffInfo with a value assigned for the forename and surname parameters
    */
    public static function with_SurForename($surname, $forename){
        $instance=new self();
        $instance->surname=$surname;
        $instance->forename=$forename;
        return $instance;
    }
    /**
    * @Description: Method that sets the an ID to the StaffInfo object
    * @Param  $id  the id assigned to the person
    */
    public function setID($id){
        $this->id=$id;
    }
    /**
    * @Description: Method that returns the value of the staff ID belonging to this StaffInfo object
    * @Return  the staff id linked to this object
    */
    public function getID(){
        return $this->id;
    }
    /**
    * @Description: Method that sets the a surname to the StaffInfo object
    * @Param  $sur  the surname of the person
    */
    public function setSurname($sur){
        $this->surname=$sur;
    }
    /**
    * @Description: Method that returns the value of the staff surname belonging to this StaffInfo object
    * @Return  the staff surname linked to this object
    */
    public function getSurname(){
        return $this->surname;
    }
    /**
    * @Description: Method that sets the a forename to the StaffInfo object
    * @Param  $fore  the forename of the person
    */
    public function setForename($fore){
        $this->forename=$fore;
    }
    /**
    * @Description: Method that returns the value of the staff forename belonging to this StaffInfo object
    * @Return  the staff forename linked to this object
    */
    public function getForename(){
        return $this->forename;
    }
    /**
    * @Description: Method that sets the location to the StaffInfo object
    * @Param  $loc  the location of the person
    */
    public function setLocation($loc){
        $this->location=$loc;
    }
    /**
    * @Description: Method that returns the value of the staff location belonging to this StaffInfo object
    * @Return  the staff location linked to this object
    */
    public function getLocation(){
        return $this->location;
    }
    /**
    * @Description: Method that sets the phone number to the StaffInfo object
    * @Param  $phone  the phone number of the person
    */
    public function setPhoneNumber($phone){
        $this->phoneNumber=$phone;
    }
    /**
    * @Description: Method that returns the value of the staff phone number belonging to this StaffInfo object
    * @Return  the staff phone number linked to this object
    */
    public function getPhoneNumber(){
        return $this->phoneNumber;
    }
    /**
    * @Description: Method that sets the email address to the StaffInfo object
    * @Param  $email  the email address of the person
    */
    public function setEmail($email){
        $this->email=$email;
    }
    /**
    * @Description: Method that returns the value of the staff email belonging to this StaffInfo object
    * @Return  the staff email address linked to this object
    */
    public function getEmail(){
        return $this->email;
    }
    /**
    * @Description: This method creates and array for this object data
    * @Return  an array containing all the parameters and values of the StafInfo object
    */
    public function toArray(){
        $data = array('ID' => $this->id, 'Surname' => $this->surname, 'Forename' => $this->forename, 'Location' => $this->location, 'PhoneNumber' => $this->phoneNumber, 'Email' => $this->email);
        return $data;
    }
    /**
    * @Description: This method returns a string containing the description of the object itself and all its attributes
    * @Return  a string containing all the parameters and values of the StafInfo object
    */
    public function toString(){
        $s="ID: {$this->id} Surname: {$this->surname} Forename: {$this->forename} Location: {$this->location} Phone Number: {$this->phoneNumber} Email: {$this->email}";
        return $s;
    }
}

?>
