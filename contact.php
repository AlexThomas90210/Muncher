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

   //user & errorMessage declared here so its out of scope of the if blocks, then can check if it was set after the if blocks and save
   $user;
   $successMessage;
   $errorMessage;

   //test subscribe
   /*
   $_POST["subscribeEmail"]  = "not  an email.com";
   */

   //test contact
   /*
   $_POST["contactName"] = "alex";
   $_POST["contactEmail"] = "wrongEmail";
   $_POST["contactMessage"] = "message";
   */

   if ( isset( $_POST["subscribeEmail"] )  ){
      //** SUBSCRIBE SUBMITED **

      //create the user , constructor takes care of everything, including throwing if its not a valid email
      try {
         $user = new User( $_POST["subscribeEmail"] );  //throws
         $user->setSubscribed( true );
         $successMessage = "Subscribed!";
      } catch (Exception $e) {
         $errorMessage = $e->getMessage();
      }
   } else if ( isset( $_POST["contactName"] ) && isset( $_POST["contactEmail"]) && isset( $_POST["contactMessage"]) ){
      //** CONTACT SUBMITED **
      //get variables , DB class will take care of sanitization
      $name = $_POST["contactName"];
      $email = $_POST["contactEmail"];
      $message = $_POST["contactMessage"];

      //create new user
      try {
         $user = new User( $email , $name ); //throws
         //send the message the user sent
         $user->sendMessage($message);
         $successMessage = "Sent! Thank you for your message!";
      } catch (Exception $e) {
         $errorMessage = $e->getMessage();
      }
   } else {
      //** BAD REQUEST **
      //not the correct post variables were set, therefore set aproprate header
      header("HTTP/1.1 400 Bad Request");
      echo "Bad Resquest";
      exit();
   }

   //if user was created save(); to persist all the changes to DB
   if ( isset($user) ) {
      $user->save();
   }

   $response = "";
   //prepare reponse
   if ( isset($successMessage) ) {
      $response = [
                  "status" => "success",
                  "message" => $successMessage
                        ];
      }
   //if error ,change the response
   if ( isset($errorMessage) ){
      $response = [
                     "status" => "error",
                     "message" => $errorMessage
                        ];
   }
   echo json_encode($response);
?>
