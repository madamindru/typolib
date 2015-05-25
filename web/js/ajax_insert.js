function clickHandlers() {
    $("a.new-exception").unbind('click');
    $("a.new-exception").click(function(event){
        event.preventDefault();
        // Make sure the form is displayed
        $('#exceptionview').show();

        // Show the "New exception…" under previous rule
        $('#exceptionview').parent().find('.new-exception').show();

        // Hide "new exception…" under current rule
        $(this).hide();

        // Move the form under current rule
        $('#exceptionview').detach().appendTo($(this).parent());
    });


    $(".delete-rule").unbind('click');
    $(".delete-rule").click(function(event) {
        code = $('#code_selector').val();
        locale = $('#locale_selector').val();
        var li = $(this).parent();
        id_rule = li.find('.rule').data('id-rule');
        $.ajax({
            url: "api/",
            type: "GET",
            data: "action=deleting_rule&locale=" + locale + "&code=" + code + "&id_rule=" + id_rule,
            dataType: "html",
            context: this,
            success: function(response) {
                if (response == "1") {
                    $(this).parent().remove();
                } else {
                    alert("Sorry, something went wrong while deleting this rule. Try again later.");
                }
            },
            error: function() {
                console.error("AJAX failure - delete rule");
            }
        });
    });

    $(".delete-exception").unbind('click');
    $(".delete-exception").click(function(event) {
        code = $('#code_selector').val();
        locale = $('#locale_selector').val();
        var li = $(this).parent();
        id_exception = li.data('id-exception');
        var rule = li.parent().parent();
        id_rule = rule.find('.rule').data('id-rule');
        $.ajax({
            url: "api/",
            type: "GET",
            data: "action=deleting_exception&locale=" + locale + "&code=" + code + "&id_rule=" + id_rule + "&id_exception=" + id_exception,
            dataType: "html",
            context: this,
            success: function(response) {
                if (response == "1") {
                    $(this).parent().remove();
                } else {
                    alert("Sorry, something went wrong while deleting this exception. Try again later.");
                }
            },
            error: function() {
                console.error("AJAX failure - delete exception");
            }
        });
    });


    $('#submitRuleException').unbind('click');
    $('#submitRuleException').click(function(event) {
        event.preventDefault();
        code = $('#code_selector').val();
        locale = $('#locale_selector').val();
        exception = $('#exception').val();
        id_rule = $('#exceptionview').parent().parent().find('.rule').data('id-rule');
        $.ajax({
            url: "api/",
            type: "GET",
            data: "action=adding_exception&locale=" + locale + "&code=" + code + "&id_rule=" + id_rule + "&content=" + exception,
            dataType: "html",
            success: function(response) {
                if (response != "0") {
                    var ul = $('#exceptionview').parent();
                    ul.append(response);
                    ul.find('#exceptionview').appendTo(ul);
                    ul.find('.new-exception').appendTo(ul);
                    clickHandlers();
                } else {
                    alert("The exception field can’t be empty.");
                }
            },
            error: function() {
                console.error("AJAX failure - add rule");
            }
        });
    });
};

$('#exceptionview').hide();
clickHandlers();

$('#locale_selector').on('change', function() {
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=get_codes&locale=" + this.value,
        dataType: "html",
        success: function(response) {
            $("#code_selector").html(response);
            clickHandlers();
            $('#exceptionview').hide();
        },
        error: function() {
            console.error("AJAX failure - get codes");
        }
    });
});

$('#code_selector').on('change', function() {
    locale = $('#locale_selector').val();
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=get_rules&locale=" + locale + "&code=" + this.value,
        dataType: "html",
        success: function(response) {
            $("#results").html(response);
            clickHandlers();
            $('#exceptionview').hide();
        },
        error: function() {
            console.error("AJAX failure - get rules");
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
    comment = $('#comment').val();
    placeholder = $('#addrule_type :selected').text();
    var inputs = new Array();
    $('input[type=text]').each(function(){
        var input = $(this);
        if(input.attr('name').toLowerCase().indexOf("input") >= 0) {
            inputs.push(input.val());
        }
    });
    var array_content = JSON.stringify(inputs);
    $('#rule').val(placeholder);
    $('#comment').val('');
    $.ajax({
        url: "api/",
        type: "GET",
        data: "action=adding_rule&locale=" + locale + "&code=" + code + "&type=" + rule_type + "&content=" + rule + "&comment=" + comment + "&array=" + array_content,
        dataType: "html",
        success: function(response) {
            $("#results").html(response);
            clickHandlers();
        },
        error: function() {
            console.error("AJAX failure - add rule");
        }
    });
});
