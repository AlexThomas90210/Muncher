<?php
class FormController {
   public $user

   private $errorMessage
   private $successMessage

   const SUCCESS_MESSAGE_SENT = "Sent! Thank you for your message!";
   const SUCCESS_SUBSCRIPTION = "Subscribed!";

   public function __construct() {
      $this->checkForSubscribeForm;
      $this->checkForContactForm;
   }

   public function $outputJSON(){
      if (isset($successMessage) ) {
         $response = [
                        "status" => "success",
                        "message" => $successMessage
                           ];
         echo json_encode($response);
      } else if ( isset($errorMessage) ) {
         $response = [
                        "status" => "error",
                        "message" => $errorMessage
                           ];
         echo json_encode($response);
      }
   }

   private function checkForSubscribeForm(){
      if ( isset( $_POST[SUBSCRIBE_EMAIL] )  ){
         //** SUBSCRIBE SUBMITED **
         //create the user , constructor takes care of everything, including throwing if its not a valid email
         try {
            $user = new User( $_POST[SUBSCRIBE_EMAIL] );  //throws
            $user->setSubscribed( true );
            $successMessage = SUCCESS_SUBSCRIPTION;
         } catch (Exception $e) {
            $errorMessage = $e->getMessage();
         }
      }
   }

   private function checkForContactForm(){
      if ( isset( $_POST[CONTACT_NAME] ) && isset( $_POST[CONTACT_EMAIL]) && isset( $_POST[CONTACT_MESSAGE]) ){
         //** CONTACT SUBMITED **
         //get variables in easier to read variable , DB class will take care of sanitization
         $name = $_POST[CONTACT_NAME];
         $email = $_POST[CONTACT_EMAIL];
         $message = $_POST[CONTACT_MESSAGE];

         //create new user
         try {
            $user = new User( $email , $name ); //throws
            //send the message the user sent
            $user->sendMessage($message);
            $successMessage = SUCCESS_MESSAGE_SENT;
         } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }
     }
   }
}
 ?>
