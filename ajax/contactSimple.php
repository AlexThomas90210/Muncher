<?php
//I was worried you would dock me marks for making something as easy as contact so complicated with 7 includes,abstract classes, interfaces , singletons & MVC. and so many comments etc
//so I made this file to show it doesnt have to be complicated

/*
//tests for contact
$_POST["contactName"] = "name";
$_POST["contactEmail"] = "valid@email.com";
$_POST["contactMessage"] = "test message";
*/

/*
//test for subscribe
$_POST['subscribeEmail']) = "this email should fail";
*/

//function to exit with error message for the ajax call
//just quick note that for mysqli querys im using this function instead of "or die("")" , purely for the user experience, user doesnt want to know the mysql error
function exitWithErrorJSON($errorMsg)
{
    echo json_encode(array(
        'status' => 'error',
        'message' => $errorMsg,
    ));
    exit();
}

function successJSON($successMsg)
{
    echo json_encode(array(
        'status' => 'success',
        'message' => $successMsg,
    ));
}

//function to validate email
function checkEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        //not an email, return error json , and exit the script
        exitWithErrorJSON('not a valid email');
    }

    return $email;
}

// check for contact form submited,
if (isset($_POST['contactName']) && isset($_POST['contactEmail']) && isset($_POST['contactMessage'])) {
    //make sure its a valid email , get variables, prepared for DB
    $email = checkEmail($_POST['contactEmail']);
    $mysqli = new mysqli('localhost', 'root', '', 'muncher');
    //sanitize
    $name = $mysqli->real_escape_string($_POST['contactName']);
    $email = $mysqli->real_escape_string($email);
    $message = $mysqli->real_escape_string($_POST['contactMessage']);

    //insert user, update the user name if its already there, incase somebody subscribed, then messaged, we wouldnt have name in that case
    $mysqli = new mysqli('localhost', 'root', '', 'muncher');
    $query = "INSERT INTO Users (name , email , subscribed)
                VALUES ('$name' , '$email' , true )
                    ON DUPLICATE KEY
                    UPDATE id=LAST_INSERT_ID(id) , name='$name' ";
    $mysqli->query($query) or exitWithErrorJSON('could not send message');
    $userId = $mysqli->insert_id;
    //insert message with the generated userId, update statement will also get the id
    $query2 = "INSERT INTO Messages (userId , messageText)
                    VALUES ('$userId' , '$message')";
    $result2 = $mysqli->query($query2) or exitWithErrorJSON('could not send message');

    //success , echo success json
    successJSON('Sent! Thank you for your message!');
// check for subscribeform submited,
} elseif (isset($_POST['subscribeEmail'])) {
    //get email prepared for db
    $email = checkEmail($_POST['subscribeEmail']);
    $mysqli = new mysqli('localhost', 'root', '', 'muncher');
    $email = $mysqli->real_escape_string($email);

    //insert into DB, ignore if email already exists
    $query = "INSERT IGNORE INTO Users (email , subscribed)
                VALUES ( '$email' , true)
                    ON DUPLICATE KEY
                    UPDATE subscribed = true ";
    $result = $mysqli->query($query) or exitWithErrorJSON('Could not subscribe!');

    //echo sucess
    successJSON('Subscribed!');
}
