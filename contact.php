<?php /*
   both conact and the subscribe forms post here.

   User's name is optional because the subscribe form does not have a name input field from the designer.
   However if a user sends us a message which does require a name, we will fill it in.
   User's subscribed is true/false because if a user unsubs to the newsletter, we dont want to delete the user as Messages table could be referencing that userId
   a user could unsub via the newletter with an "unsubscribe" at the bottom of the email (not implementing the actual email this just theoretical)
   When a user sends us a message via contact, if its a new user , we are going to be very sneaky and automatically subscribe him to newsletter
   */
   require_once('./PHPIncludes/Savable.abstract.class.php');
   require_once('./PHPIncludes/DB.interface.php');
   require_once('./PHPIncludes/User.class.php');
   require_once('./PHPIncludes/MysqliDB.class.php');
   require_once('./PHPIncludes/DB.singleton.class.php');

//test
$_POST["subscribeEmail"] = "subscribeEmailTest";

   //user declared here so its out of scope of the if blocks, then can check if it was set after the if blocks and save
   $user;

   if ( isset( $_POST["subscribeEmail"] )  ){
      //subsribe form was submitted

      //create the user , constructor takes care of everything
      $user = new User( $_POST["subscribeEmail"] );
      $user->setSubscribed( true );
   }

   if ( isset( $_POST["contactName"] ) && isset( $_POST["contactEmail"]) && isset( $_POST["contactMessage"]) ){
      //the contact form was submited
      //get variables , DB class will take care of sanitization
      $name = $_POST["ContactName"];
      $email = $_POST["ContactEmail"];
      $message = $_POST["ContactMessage"];

      //create new user
      $user = new User( $email , $name );
      //send the message the user sent
      $user->sendMessage($message);
   }

   //if user was created save(); to persist all the changes to DB
   if ( isset($user) ) {
      $user->save();
   }
?>
