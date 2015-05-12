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
            console.log("AJAX failure - get codes");
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
            console.log("AJAX failure - get rules");
        }
    });
});
