<?php
//because of how im using the Savable abstract class, I want to completely hide the DB class from all the files , except for the Savable implementation method & in constructors of Savable objects.
//to do this I want to use a singleton DB object so im not using dependecy injection everytime I make a new Savable object , or keeping a reference to seperate DB objects in every savable class
//the reason im not using a singleton version of Mysqli is so its easy to swap out the Mysqli for any other database technology.
//I was using a Mysqli::sharedinstance(); but I could see that if I wanted to change it to PDO then I would have to change that line everywhere in the project,which could potentially get messy
//so instead im going to use this class which is purely there to call the static function sharedInstance , which is always going to give me a DB object that implements DBInterface

class DB {
    //static shared instance to create a singleton
    public static function sharedInstance() {
        //static variable inside function allows that variable to be available next time this function is called
        static $db;
        //check if $db has been initialised before, if not init it
        if (!isset($db)) {
            $db = new MysqliDB(); //<=====  Change code here to use different databases
        }
        //make sure $db is safe to use
        if ($db instanceof DBInterface) {
            // $db is safe to use
            return $db;
        } else {
            // $db does not implement DBInterface , die giving error message with the name of the class that is not safe to use
            $nameOfClass = get_class($db);
            die('Fatal Error , Database '.$nameOfClass.' does not implement DBInterface');
        }
    }
}
