$(document).ready(function(){

   //function to process the data from the server and output the message into the target element
   processAjaxFormResponse = function(data , outputTarget){
      //Success , display status
      if  (data.status == "success"){
         outputTarget.addClass("success-message")
                              .html(data.message);

      } else if (data.status == "error") {
         //error set the error
         outputTarget.addClass("error-message")
                              .html(data.message);
      }
   };


   //Modal contact form Ajax
   $('#contactForm').on("submit",function(e) {
      $("#contactResponse").removeClass("error-message success-message")
                                            .html("Sending...");
      form = $(this);
      $.ajax({
         type: form.attr('method'),
         url: form.attr('action'),
         data: form.serialize(),

         success: function(data) {
            data = $.parseJSON(data);
            processAjaxFormResponse(data , $("#contactResponse") );
            if (data.status == "success") {
               //success , wait a little bit so user can see the success message
               setTimeout( function(){
                  //hide modal
                  $('.modal').modal('hide');
               }, 1000);
            }
         }
      });
   });


   //Subscribe Inline Form Ajax
   $('#subscribeForm').on("submit",function(e) {
      $("#subscribeResponse").removeClass("error-message success-message")
                                            .html("Submiting...");
      form = $(this);
      $.ajax({
         type: form.attr('method'),
         url: form.attr('action'),
         data: form.serialize(),

         success: function(data) {
            data = $.parseJSON(data);
            processAjaxFormResponse(data , $("#subscribeResponse"));
         }
      });
   });



});
