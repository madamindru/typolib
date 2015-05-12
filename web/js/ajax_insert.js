/* Attach a submit handler to the form */
$("#mainform").submit(function(event) {

    /* Stop form from submitting normally */
    event.preventDefault();

    /* Send the data using post and put the results in a div */
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=codes&locale=fr",
        dataType: 'html',
        success: function(response) {
            $("#code_selector").html(response);
        },
        error:function() {
            alert("failure - get code");
        }
    });
});

$('#locale_selector').on('change', function() {
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=codes&locale=" + this.value,
        dataType: "html",
        success: function(response) {
            $("#code_selector").html(response);
        },
        error: function() {
            alert("failure - get codes");
        }
    });
});

$('#code_selector').on('change', function() {
    locale = $('#locale_selector').value;
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=rules&locale=" + locale + "&code=" + this.value,
        dataType: "html",
        success: function(response) {
            $("#results").html(response);
        },
        error: function() {
            alert("failure - get rules");
        }
    });
});
