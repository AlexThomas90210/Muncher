<?php
/*
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D15126833
* Date : 2016/04/26
* Ref:  http://www.w3schools.com/php/filter_validate_email.asp
        http://stackoverflow.com/questions/2385047/when-will-destruct-not-be-called-in-php
*/

/* For the User class I wanted to try an Active Records style design that i was reading about recently , total over kill for this project considering how basic it is but just for fun & practice.
basically the developer just talks to the User class and it takes care of persisting itself to the database.
->save(); is expected to be called after working with a User instance to lower the database requests .
->save() is not automatically called after using a setter because if somebody did setName(); setEmail(); setSubscribed(); that would call the databse 3 times
where as if ->save() is manual, the developer can choose when to call save() to be more efficient
this makes the model classes a little more complicated but makes working with them cleaner and require less code

*/

class User extends Savable {
    private $id;
    private $name;
    private $email;
    private $subscribed;

    const ERROR_NOT_VALID_EMAIL = 'Not a valid email';

    public function __construct($email, $name = null) {
        //constructor throws
        //due to one of the form fields not having a name parameter in the design , I need to consider that name will be NULL sometimes

        //first we are checking that the email is valid
        //REF: http://www.w3schools.com/php/filter_validate_email.asp
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            //not a valid email, throw exception
            throw new Exception(self::ERROR_NOT_VALID_EMAIL);
        }

        //the next try has is for the DB , we handle the error here, creating a new user if the email is not in db
        try {
            //try to get the user
            $DBUserRow = DB::sharedInstance()->getUser($email); //throws if user not in db , returns the associative array of the row if user found
            //user found
            $this->id = $DBUserRow['id'];
            $this->name = $DBUserRow['name'];
            $this->email = $DBUserRow['email'];
            $this->subscribed = $DBUserRow['subscribed'];

            //now our user class is exactly what is in the DB , check if the user supplied a name
            if ($name != null) {
                //a name was supplied in constructer, I do it like this so that the class is aware if what we have !=  what is in the DB
                //setName will take care of checking if its a new name is different and setting the $changed for save() function to be aware somehting is different
                $this->setName($name);
            }
        } catch (Exception $e) {
            //no user in DB , create a new one , $this->id is not set because it is lazy loaded, because it is expected to call save after working with a user
            $this->name = $name;
            $this->email = $email;
            $this->subscribed = true; //automatically subscribe any new user

            //set new true to true so save function is aware it needs to create
            $this->setNewTrue();
        }
    }

    public function __destruct() {
        //safety net incase developer does not call save() by accident, if the developer did call save, this will not call the db in that case so its not wasteful
        $this->save();
        //I wanted to just have save(); in the destruct and not need to manually call save(); but after a bit of research it turns out destruct is not called 100% of the time
        // REF: http://stackoverflow.com/questions/2385047/when-will-destruct-not-be-called-in-php
    }

    public function getId() {
        //id is lazy loaded. In the case that a new User was made that didnt come from the db,check if its empty , if it is call save, which will set the id
        if (is_null($this->id)) {
            $this->save();
        }

        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSubscribed() {
        return $this->subscribed;
    }

    public function setName($name) {
        //for all the setters I need to check if the new value is actually different because
        // using the Savable method setChangeTrue makes the User instance call an update on the database next time save() is called,
        // which is not needed if nothing has changed

        //check if new variable is different
        if ($this->name != $name) {
            $this->name = $name;
            $this->setChangedTrue();
        }
    }

    public function setEmail($email) {
        //check if new varable is different
        if ($this->email != $email) {
            $this->email = $email;
            $this->setChangedTrue();
        }
    }

    public function setSubscribed($subscribe) {
        //check if new varable is different
        if ($this->subscribed != $subscribe) {
            $this->subscribed = $subscribe;
            $this->setChangedTrue();
        }
    }

    public function sendMessage($message) {
        $userId = $this->getId();
        DB::sharedInstance()->insertMessage($userId, $message);
    }

   /*** Savable abstract class Implementation ***/

   protected function insertIntoDB() {
        //since the class needs to be inserted into DB , the userId is not set yet, the db->createUser returns the inserted id.
        $this->id = DB::sharedInstance()->createUser($this);
        //im not setting the id inside createUser because id is private , also dont want to have a public setter for setting id's
   }

    protected function updateInDB() {
        DB::sharedInstance()->updateUser($this);
    }
}
