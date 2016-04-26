/*
* Author: Alex Thomas
* Assignment: WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D15126833
* Date : 2016/04/26
* Ref:  http://stackoverflow.com/questions/2855865/jquery-regex-validation-of-e-mail-address
*/

$(document).ready(function(){
    //I had all my functions in a different file , but put it all into main to save a http request

    //function to check valid email
    //http://stackoverflow.com/questions/2855865/jquery-regex-validation-of-e-mail-address
    isValidEmailAddress = function (emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    };

    //function to check if input is valid, its its not it adds the input--invalid class and returns why its invalid , if it is add the input--valid class to it
    validateInput = function(input){
        //if the input type is submit just return we dont care about those
        if (input.prop('type') === 'submit' || input.prop('type') == 'button'){
            return null;
        }
        //check if input is required and empty
        if ( input.prop( 'required') === true && !input.val() ) {
            input.addClass("input--invalid")
                    .removeClass("input--valid");
            //return error message
            return "Please fill in all the required fields";
        }
        //check valid email
        if ( input.prop( 'type') === "email" && !isValidEmailAddress( input.val() )   ) {
            input.addClass("input--invalid")
                    .removeClass("input--valid");
            return "That is not a valid email !";
        }
        //can add some more valdiators here but there is no need because email && required is all that needed in the website atm
        //if function gets this far the input is valid , add the necesarry classes, since there is no return from the function I can check if it returns nil to see if its valid
        input.addClass("input--valid")
                .removeClass("input--invalid");
        return null;
    };

    //function to check if form is valid
    checkFormIsValid = function( jqueryForm , outputTarget ){
        //remove any error or success message class to the output target incase its already set from a previous attempt from user
        outputTarget.removeClass("error-message success-message")
                        .html("Sending...");
        //set isValid here so the loop can change it
        isValid = true;
        //iterate over all the inputs
        jqueryForm.find( ':input' ).each( function(i){
            errorMessage = validateInput( $(this) );
            if ( errorMessage !== null ) {
                //got an error message during the validate input therefor the form is invalid , put the error message in the outputTarget
                displayErrorMessage( outputTarget , errorMessage );
                //focus the input for the user
                $(this).focus();
                //set is valid to false
                isValid = false;

            }
        });
        return isValid;
    };

    //function for the ajax Error handler, simply saying there was an error not to much details
    displayErrorMessage = function(outputTarget , message) {
        outputTarget.addClass("error-message")
                        .html( message );
    };

    //function to process the data from the server and output the message into the target element
    processAjaxFormResponse = function(data , form , outputTarget){
        if  (data.status == "success"){
            //user successfully reached his goal so disable the form
            form.find(':input')
                .removeClass('input--valid input--invalid')
                .not('[data-dismiss]')
                .prop('disabled' , true);
            //Success , display message
            outputTarget.addClass("success-message")
                            .html(data.message);

        } else if (data.status == "error") {
            //Error, display error
            displayErrorMessage(outputTarget , data.message);
        }
    };

    //Modal contact form Ajax submit handler
    $('#contactForm').on("submit",function(e) {
        outputTarget = $("#contactResponse");
        form = $(this);

        //check if the form is valid
        if  ( !checkFormIsValid(form, outputTarget) ) {
            //something is wrong with the form , exit function
            return;
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),

            success: function(data) {
                data = $.parseJSON(data);
                processAjaxFormResponse(data , form ,outputTarget );
                if (data.status == "success") {
                    //success , wait a little bit so user can see the success message before closing modal
                    setTimeout( function(){
                        //hide modal
                        $('.modal').modal('hide');
                    }, 1000);
                }
            },
            error: function(request, status, error) {
                if  (request.readyState === 0 ) {
                    displayErrorMessage( outputTarget , "Error! Check your connection !");
                }
                displayErrorMessage( outputTarget , "Error! Someting went wrong !");
            }
        });
    });

    //Subscribe Inline Form Ajax submit handler
    $('#subscribeForm').on("submit",function(e) {
        outputTarget = $("#subscribeResponse");
        form = $(this);

        //check the form
        if  ( !checkFormIsValid(form, outputTarget) ) {
            //something is wrong with the form , exit function
            return;
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),

            success: function(data) {
                data = $.parseJSON(data);
                processAjaxFormResponse(data , form ,outputTarget );
            },
            error: function(request, status, error) {
                if  (request.readyState === 0 ) {
                    displayErrorMessage( outputTarget , "Error! Check your connection !");
                }
                displayErrorMessage( outputTarget , "Error! Something went wrong !" );
            }
        });
    });

    //get all the forms on page and make all the inputs delegate their keyup events and autocomplete events to the form to validate the input as user types
    //im using change because its easier to handle all the different types of browser autocompletes altho sometimes it doesnt work......
    //could not figure out how to deal with mouse click on the auto fill, it works on the modal contact form but not on the inline subscribe form
    //it only validates if you press enter to select the auto fill you want , else if you click it with your mouse the validator only kicks off after the next keyup
    $('form').on( 'keyup change' , ":input" , function(event){
        validateInput( $(event.target) );
    });
});
