$(document).ready(function(){
   //contact form submit
   $('#contactForm').on("submit",function(e) {
      form = $(this);
      $.ajax({
         type: form.attr('method'),
         url: form.attr('action'),
         data: form.serialize(),

         success: function(data, status) {
            alert("hi");
         }
      });
   });



   $('#subscribeForm').on("submit",function(e) {
      form = $(this);
      $.ajax({
         type: form.attr('method'),
         url: form.attr('action'),
         data: form.serialize(),

         success: function(data, status) {
            alert("hi");
            alert(data);
            alert(status);
         }
      });
   });



});
