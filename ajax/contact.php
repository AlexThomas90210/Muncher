<?php
/*
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D15126833
* Date : 2016/04/26
* Ref:
*/
/*
please read the readme before looking at this file

both contact and the subscribe forms post here.

User's name is optional because the subscribe form does not have a name input field from the designer.
However if a user sends us a message which does require a name, we will fill it in.
User's subscribed is true/false because if a user unsubs to the newsletter, we dont want to delete the user the Messages table could be referencing that userId
a user could unsub via the newletter with an "unsubscribe" at the bottom of the email (not implementing the actual email this just theoretical)
When a user sends us a message via contact, if its a new user , we are going to be very sneaky and automatically subscribe him to newsletter
*/

//model
require_once '../private/PHP/Model/Constants.php';
require_once '../private/PHP/Model/Savable.abstract.class.php';
require_once '../private/PHP/Model/DB.interface.php';
require_once '../private/PHP/Model/User.class.php';
require_once '../private/PHP/Model/MysqliDB.class.php';
require_once '../private/PHP/Model/DB.singleton.class.php';
//controller
require_once '../private/PHP/Controllers/FormController.php';

$formController = new FormController();
$formController->outputJSON();
$formController->user->save();
