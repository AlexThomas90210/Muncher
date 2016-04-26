<?php
/*
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D15126833
* Date : 2016/04/26
* Ref:
*/
//please read the readme before looking at this file

class FormController {
    const SUCCESS_MESSAGE_SENT = "Sent! Thank you for your message!";
    const SUCCESS_SUBSCRIPTION = "Subscribed!";

    public $user;
    private $errorMessage;
    private $successMessage;

    public function __construct() {
        $this->checkForSubscribeForm();
        $this->checkForContactForm();
    }

    //output json for the ajax handler to use on the front end
    public function outputJSON() {
        if  ( isset($this->successMessage) ) {
            //we were successfull
            $response = [
                "status" => "success",
                "message" => $this->successMessage
            ];
            echo json_encode($response);
        } else if ( isset($this->errorMessage) ) {
            //we have an error
            $response = [
                "status" => "error",
                "message" => $this->errorMessage
            ];
            echo json_encode($response);
        }
    }

    private function checkForSubscribeForm() {
        if ( isset( $_POST[SUBSCRIBE_EMAIL] )  ){
            //** SUBSCRIBE SUBMITED **
            //create the user , constructor takes care of everything, including throwing if its not a valid email
            try {
                $this->user = new User( $_POST[SUBSCRIBE_EMAIL] );  //throws
                $this->user->setSubscribed( true );
                $this->successMessage = FormController::SUCCESS_SUBSCRIPTION;
            } catch (Exception $e) {
                $this->errorMessage = $e->getMessage();
            }
        }
   }

    private function checkForContactForm() {
        if ( isset( $_POST[CONTACT_NAME] ) && isset( $_POST[CONTACT_EMAIL]) && isset( $_POST[CONTACT_MESSAGE]) ){
            //** CONTACT SUBMITED **
            //get variables in easier to read variable , DB class will take care of sanitization
            $name = $_POST[CONTACT_NAME];
            $email = $_POST[CONTACT_EMAIL];
            $message = $_POST[CONTACT_MESSAGE];

            //create new user
            try {
                $this->user = new User( $email , $name ); //throws
                //send the message the user sent
                $this->user->sendMessage($message);
                $this->successMessage = FormController::SUCCESS_MESSAGE_SENT;
            } catch (Exception $e) {
                $this->errorMessage = $e->getMessage();
            }
         }
    }
}
?>
