$(document).ready(function(){
    "use strict";

    const $addForm = $("#form_add_string");

    if($addForm.length > 0) {
        _validate_form($("#form_add_string"),
            {
                index: "required",
                "value[english]": "required",
            }
        );
    }


    $("#type_to_search").removeAttr("disabled");

    $(".keyword a.editable").on("click", function(e){
        e.preventDefault();
        var lang = $(this).data("lang");
        var message = $(this).data("message");
        var text = $(this).text();
        var $input = $(this).closest(".field_wrapper").find("input");
        $input.val(text);
        save_one($input, lang, message);
    });

    $("[name=show_empty_only]").on("change", function(){
        if($(this).is(":checked"))
            $("[name=show_updated_only]").prop("checked", false);
    });

    $("[name=show_updated_only]").on("change", function(){
        if($(this).is(":checked"))
            $("[name=show_empty_only]").prop("checked", false);
    });
});

var input_val =  '';
function save_initial_value(ele){
    input_val = '';
    var $ele = $(ele);
    input_val = $ele.val();
}

function save_one(ele, lang, message){
    var $ele = $(ele);
    var value = $ele.val();
    var id = $ele.data("id");
    var index = $ele.data("index");
    console.log(value);
    if(value !== input_val) {
        $.ajax({
            url: admin_url + 'translations/save_one',
            type: 'post',
            dataType: 'json',
            data: {id: id, index: index, value: value, lang: lang},
            success: function (response) {
                $ele.closest(".field_wrapper").find('.updated_wrapper').html(response.html);
                input_val = '';
                if(response.needs_publishing)
                    $("#needs_publishing_warning").removeClass('hide');
                else
                    $("#needs_publishing_warning").addClass('hide');
                alert_float("success", message);
            }
        })
    }
}

function undo_one(ele, message){
    var $ele = $(ele);
    var id = $ele.data("id");
    $.ajax({
        url: admin_url + 'translations/undo_one/'+id,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $ele.closest(".field_wrapper").find("input").val(response.value);
            $ele.closest(".updated_wrapper").html("");
            if(response.needs_publishing)
                $("#needs_publishing_warning").removeClass('hide');
            else
                $("#needs_publishing_warning").addClass('hide');
            alert_float("success", message);
        }
    })
}

function attempt_change(folder, warning_id){
    $.ajax({
        url: admin_url + 'translations/attempt_change_permissions/',
        type: 'post',
        data: {folder: folder},
        dataType: 'json',
        success: function (response) {
            if(response.success){
                alert_float("success", response.message);
                $("#" + warning_id).remove();
            }
            else
                alert_float("warning", response.message);
        }
    })
}