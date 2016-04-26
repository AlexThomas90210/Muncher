<?php
/*
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D15126833
* Date : 2016/04/26
* Ref:
*/

interface DBInterface {
    public function createUser($user);  //returns a newly inserted id
    public function getUser($email); //throws if no user found , returns associative array of row if user found
    public function insertMessage($userId, $message); // returns inserted id of message
    public function updateUser(User $user); //returns true on success , false on fail
}
