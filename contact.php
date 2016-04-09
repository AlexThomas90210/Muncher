<?php /*
   both contact and the subscribe forms post here.

   User's name is optional because the subscribe form does not have a name input field from the designer.
   However if a user sends us a message which does require a name, we will fill it in.
   User's subscribed is true/false because if a user unsubs to the newsletter, we dont want to delete the user as Messages table could be referencing that userId
   a user could unsub via the newletter with an "unsubscribe" at the bottom of the email (not implementing the actual email this just theoretical)
   When a user sends us a message via contact, if its a new user , we are going to be very sneaky and automatically subscribe him to newsletter
   */
   //model
   require_once('.PHP/Model/Constants.php');
   require_once('./PHP/Model/Savable.abstract.class.php');
   require_once('./PHP/Model/DB.interface.php');
   require_once('./PHP/Model/User.class.php');
   require_once('./PHP/Model/MysqliDB.class.php');
   require_once('./PHP/Model/DB.singleton.class.php');
   //controller
   require_once('./PHP/Controllers/FormController.php');

   $formController = new FormController();
   $formController->outputJSON();
   $formController->user->save();
?>
