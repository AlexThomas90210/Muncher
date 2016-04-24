$(document).ready(function(){

    //function to check valid email
    //http://stackoverflow.com/questions/2855865/jquery-regex-validation-of-e-mail-address
    isValidEmailAddress = function (emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    };

    //function to check if form variables are empty
    checkFormIsValid = function( jqueryForm , outputTarget ){
        //remove any error or succes message class to the output target incase its already set from a previous attempt from user
        outputTarget.removeClass("error-message success-message")
                        .html("Sending...");

        //
        isValid = true;
        //iterate over all the inputs
        jqueryForm.find( ':input' ).each( function(i){
            //check if input is required and if its empty
            if ( $(this).prop( 'required') === true && !$(this).val() ) {
                displayErrorMessage( outputTarget , "Please fill in all required fields !");
                //focus on input
                $(this).focus();
                //set value outside of loop to indicate something is wrong
                isValid = false;
                //break the loop
                return false;
            }
            //check if its a input of type email and the value is not a valid email
            if ( $(this).prop( 'type') === "email" && !isValidEmailAddress( $(this).val() )   ) {
                displayErrorMessage( outputTarget , "That is not a valid email !");
                $(this).focus();
                isValid = false;
                return false;
            }
        });
        if (isValid){
            //form is valid
            return true;
        } else {
            //form is not valid
            return false;
        }
    };

    //function to process the data from the server and output the message into the target element
    processAjaxFormResponse = function(data , outputTarget){
        if  (data.status == "success"){
            //Success , display message
            outputTarget.addClass("success-message")
                            .html(data.message);

        } else if (data.status == "error") {
            //Error, display error
            displayErrorMessage(outputTarget , data.message);
        }
    };

    //function for the ajax Error handler, simply saying there was an error not to much details
    displayErrorMessage = function(outputTarget , message) {
        outputTarget.addClass("error-message")
                        .html( message );
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
                processAjaxFormResponse(data , outputTarget );
                if (data.status == "success") {
                    //success , wait a little bit so user can see the success message
                    setTimeout( function(){
                        //hide modal
                        $('.modal').modal('hide');
                    }, 1000);
                }
            },
            error: function(request, status, error) {
                displayErrorMessage( outputTarget , "Error! Please check your connection or try again later");
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
                processAjaxFormResponse(data , outputTarget );
            },
            error: function(request, status, error) {
                displayErrorMessage( outputTarget , "Error! Please check your connection or try again later" );
            }
        });
    });
});
