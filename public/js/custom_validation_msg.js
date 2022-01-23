jQuery.extend(jQuery.validator.messages, {
    required: $('#field_required_txt').val(),
    remote: "Please fix this field.",
    email: $('#email_validate_txt').val(),
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: $('#validate_equalto_txt').val(),
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format($("#maxlength_validate_txt").val()),
    minlength: jQuery.validator.format($("#minlength_validate_txt").val()),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format($("#max_validate_txt").val()),
    min: jQuery.validator.format($("#min_validate_txt").val())
});