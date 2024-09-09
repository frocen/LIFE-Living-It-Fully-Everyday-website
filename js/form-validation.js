// Wait for the DOM to be ready
$(function() {
    // Initialize form validation on the registration form.
    // It has the name attribute "registration"
    $("form[name='registration']").validate({
      // Specify validation rules
      rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        address: "required",
        Enquiry: "required",
        Phone: {
          required: true,
          minlength: 10,
          maxlength: 10,
          digits: true
        },
        email: {
          required: true,
          // Specify that email should be validated
          // by the built-in "email" rule
          email: true
        }
      },
      // Specify validation error messages
      messages: {
        address: "Please enter your address",
        Enquiry: "Please enter your enquiry",
        Phone: {
          required: "Please enter your phone number",
          digits: "contact number must be numbers",
          minlength: "Your phone number must be at least 10 characters long",
          maxlength: "Your phone number must be at least 10 characters long"
        },
        email: "Please enter a valid email address"
      },
      // Make sure the form is submitted to the destination defined
      // in the "action" attribute of the form when valid
      submitHandler: function(form) {
        form.submit();
      }
    });
});