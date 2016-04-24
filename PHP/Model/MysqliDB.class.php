<?php
/* I actually took my db class from my previous IWP project and changed it a little to suit this project

2 tables in mysql : Users & Messages
Users has : id, email , name(Optional) , subscribed(true/false)
Messages has : id, userId , messageText

**Functions**
    createUser($email , $name = NULL )  -> returns inserted id
    getUser($email)  -> returns associative array of user fields
    updateUser( User $user) -> returns true if succesfull
    insertMessage( $userId, $message) returns insertedId


I am subclassing the mysqli class as I see no reason not too, this will allow me to call $this->function instead of $this->mysqli->function
*/
class MysqliDB extends mysqli implements DBInterface
{
    //Credentials
    const DB_HOST = 'localhost';
    const DB_USERNAME = 'root'; //would obviously change this but Im not sure how it works for you when I give you the sql file so im leaving it standard
    const DB_PASSWORD = '';     //same as the username ,would normally change this
    const DB_NAME = 'Muncher';

    //Error constants
    const ERROR_USER_NOT_FOUND = 'Error: User not found';
    const ERROR_CORRUPT_DATA = 'Error: Corrupt Data';

    public function __construct()
    {
        //as this is a subclass of mysqli,call the parent __contruct
        parent::__construct(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_NAME);
        //check here if there is an error
        if ($this->connect_error) {
            die('Connection Error ( ERROR Number: '.$this->connect_errno.' ) '.$this->connect_error);
        }
    }

    public function __destruct()
    {
        //checking that there is no connection error in the first place else trying to close prompts an error if the connection wasnt open/failed in the first place
        if (!$this->connect_error) {
            $this->close();
        }
    }

    //create user
    public function createUser($user)
    {
        //sanitize the variables
        $name = $this->real_escape_string($user->getName());
        $email = $this->real_escape_string($user->getEmail());

        //any user that is created will automatically be subscribed to the news letter
        $subscribed = true;
        $query = "INSERT INTO Users(name,email,subscribed)
                    VALUES ('$name','$email','$subscribed')";
        $result = $this->query($query) or die('ERROR: '.$this->error);
        //return new ID
        return $this->insert_id;
    }

   //Get the user by email
   public function getUser($email)
   {
       //sanitize variables
        $email = $this->real_escape_string($email);

       $query = "SELECT *
                    FROM Users
                        WHERE email='$email'";
       $result = $this->query($query) or die('ERROR : '.$this->error);

        //if rows == 1, user is found , else courpt data or no user
        switch (mysqli_num_rows($result)) {
        case 0 :
            //user not found
            throw new Exception(self::ERROR_USER_NOT_FOUND);
            break;
        case 1 :
            //user found in database ,get his details
            return mysqli_fetch_assoc($result);
            break;
        default:
            //should never happen unless the database is corrupted with duplicate entries for email address
            die(self::ERROR_CORRUPT_DATA);
            break;
        }
   }

    //put message in messages table with the users id
    public function insertMessage($userId, $message)
    {
        //sanitize variables, userId not coming from user but better to be safe
        $userId = $this->real_escape_string($userId);
        $message = $this->real_escape_string($message);

        $query = "INSERT INTO Messages(userId , messageText)
                    VALUES ('$userId' , '$message')";
        $result = $this->query($query) or die('ERROR: '.$this->error);

        //return the id of message
        return $this->insert_id;
    }

    //update the name of the user
    public function updateUser(User $user)
    {
        //sanitize all variables
        $userId = $this->real_escape_string($user->getId);
        $name = $this->real_escape_string($user->getName);
        $email = $this->real_escape_string($user->getEmail);
        $subscribed = $this->real_escape_string($user->isSubscribed);

        $query = "UPDATE Users
                    SET name='$name' , email='$email' , subscribed='$subscribed' ,
                    WHERE id='$userId' ";
        $result = $this->query($query) or die('ERROR: '.$this->error);

        //return true/false
        return $result;
    }
}
