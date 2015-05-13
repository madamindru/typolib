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
    locale = $('#locale_selector').val();
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

$('#addrule_type').on('change', function() {
    rule_type = $('#addrule_type :selected').text();
    $('#rule').val(rule_type);
});

$('#submitRule').click(function(event) {
    event.preventDefault();
    code = $('#code_selector').val();
    locale = $('#locale_selector').val();
    rule_type = $('#addrule_type').val();
    rule = $('#rule').val();
    placeholder = $('#addrule_type :selected').text();
    $('#rule').val(placeholder);
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=adding_rule&locale=" + locale + "&code=" + code + "&type=" + rule_type + "&content=" + rule,
        dataType: "html",
        success: function(response) {
            $("#results").html(response);
        },
        error: function() {
            console.log("AJAX failure - add rule");
        }
    });
});
