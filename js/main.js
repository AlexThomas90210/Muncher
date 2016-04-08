$(document).ready(function(){

   //function to process the data from the server and output the message into the target element
   processAjaxFormResponse = function(data , outputTarget){
      //Success , display status
      if  (data.status == "success"){
         outputTarget.css("color","green")
                              .html(data.message);

      } else if (data.status == "error") {
         //error set the error
         outputTarget.css("color","red")
                              .html(data.message);
      }
   };


   //Modal contact form Ajax
   $('#contactForm').on("submit",function(e) {
      $("#contactResponse").html("Sending...");
      form = $(this);
      $.ajax({
         type: form.attr('method'),
         url: form.attr('action'),
         data: form.serialize(),

         success: function(data) {
            data = $.parseJSON(data);
            processAjaxFormResponse(data , $("#contactResponse") );
            if (data.status == "success") {
               $("#contact").delay( 1000 )
                                    .queue(function(){
                                       $(this).modal('hide');
                                    });
            }
         }
      });
   });


   //Subscribe Inline Form Ajax
   $('#subscribeForm').on("submit",function(e) {
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
