/**
 * @Author Olusola Akinmolayan
 * Override Jquery Validator display for wrong user input to highlight properly
 */
$.validator.setDefaults({
    highlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-error');
        }
    },
    ignore: ":hidden:not(.minified_summernote),.note-editable.panel-body,.note-editor*"
});

$.validator.addMethod('filesize', function (value, element, param) {
    // param = size (in bytes)
    // element = element to validate (<input>)
    // value = value of the element (file name)
    return this.optional(element) || (element.files[0].size <= param)
}, 'Please upload a file not greater than the specified size');

$.validator.addMethod("noWhiteSpaceBegin", function (value, element) {
    return value.indexOf(" ") !== 0;
}, "This field must not begin with a whitespace");

$.validator.addMethod("wordCount", function (value, element, params) {
    var typedWords = $.trim(value).length ? value.split(/,|\s+|,\s+/g).length : 0;
    if (typedWords <= params[0]) return true;
}, "Only a maximum of {0} words allowed.");

$.validator.addMethod("greaterThan",
    function (value, element, params) {

        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }

        return isNaN(value) && isNaN($(params).val())
            || (Number(value) > Number($(params).val()));
    }, 'Must be greater than {0}.');

$.validator.addMethod("greaterThanDate",
    function (value, element, params) {
        if(value === '') return true;

        value = value.split("/").reverse().join("-");
        var compareValue = $(params).val().split("/").reverse().join("-");

        return new Date(value) > new Date(compareValue);
    }, 'Must be greater than {0}.');

function populateDropdown(parent, child, route) {
    var id = parent.val()
    child.show().html('<option value="">Loading ...</option>');
    $.getJSON(route, {id: id}, function (data) {
        var options = '';
        $.each(data, function (key, val) {
            options += '<option value="' + key + '">' + val + '</option>';
        });
        child.html(options);
    })

}

function moduleSwitcher(route, user_id) {
    $(".modules").change(function () {
        var module = $(this).val();

        $('.loggable').show().html('<option value="">Loading ...</option>');
        $.getJSON(route, {module: module, user_id: user_id}, function (data) {
            var options = '';
            $.each(data, function (key, val) {
                options += '<option value="' + key + '">' + val + '</option>';
            });
            $(".loggable").html(options);
        })

    });
}


function disableSwitcherForModule(module) {
    switch (module) {
        case 'SystemAdmin':
            $('.loggable').hide();
            break
    }
}

function getTaskCount(route) {
    $.getJSON(route, function (data) {
        $.each(data, function (key, val) {
            $('.' + key).html(val);
        });
    });
}

function showTaskList(route) {
    $('.show-tasks').on('click', function () {
        $('.dropdown-menu li').hide();
        $('.footer').show();
        $('.loading').show();
        var t_type = $(this).attr('data-type');
        $.post(route, {type: t_type}, function (data) {
            $('.loading').hide();
            $('#' + t_type).prepend(data);
        })
    });
}

function sessionTimeOut(alive_route, logout_route, redir_route, inactivity_timeout) {
    $.sessionTimeout({
        title: 'User Inactivity',
        message: 'Warning! Due to inactivity, your session has expired. Please click on the "stay connected" button or move your mouse cursor to continue"',
        keepAliveUrl: alive_route,
        logoutUrl: logout_route,
        redirUrl: redir_route,
        keepAliveInterval: 900000,
        keepAlive: true,
        warnAfter: inactivity_timeout * 60000, //15min
        redirAfter: (inactivity_timeout + 5) * 60000, //1min
    });
}

function goBack() {
    window.history.back();
}

function formValidation(formSelctor) {
    var form = formSelctor;
    var error = $('.alert-danger', form);
    var success = $('.alert-success', form);

    return form.validate({
        doNotHideMessage: true,
        focusInvalid: true,
        invalidHandler: function (event, validator) {
            // Display error message on form submit
            success.hide();
            error.show();
            alert('You missed some fields. They have been highlighted');
        },
    });
}

function createInputArray(selector) {
    var items = [];
    $('#myForm').find('.' + selector).each(function () {
        var row = {};
        $(this).find('input,select,textarea').each(function () {
            row[$(this).attr('name')] = $(this).hasClass('mask-input') ? $(this).maskMoney('unmasked')[0] : $(this).val();
        });
        items.push(row);
    });
    return items;
}

function createAutoCompleteSearchProgress(inputSelector){
    var wrapper = '<div class="autocomplete-spin-wrap"></div>';
    inputSelector.wrap(wrapper);
    /*$("<i class=\"icon-spinner icon-spin autocomplete-spinner-1\" style=\"display: none; \"></i>").insertAfter(inputSelector);*/
    $("<span class=\"btn btn-xs btn-inverse autocomplete-spinner autocomplete-button\" style=\"display: none; \"><i class='icon-spinner icon-spin'></i> SEARCHING </span>").insertAfter(inputSelector);
    $("<span class=\"btn btn-xs btn-success autocomplete-add-button autocomplete-button\" style=\"display: none; \"><i class='icon-plus'></i> ADD RECORD </span>").insertAfter(inputSelector);
    $("<span class=\"btn btn-xs btn-success autocomplete-added-button autocomplete-button\" style=\"display: none; \"><i class='icon-check'></i> ADDED </span>").insertAfter(inputSelector);
    return inputSelector.parent().find(".autocomplete-spinner");
}

function autoCompleteInputLookup(inputSelector, valueSelector, route, saveInputText, notice) {
    saveInputText = (typeof saveInputText !== 'undefined') ? saveInputText : false;

    if((typeof notice === 'undefined')){
        notice = (saveInputText) ? "No results found, click on  'ADD RECORD' to add as a base record." : "No Results";
    }

    notice = '<span style="color:red">'+notice+'</span>';

    inputSelector.on('keyup',function(){
        var input = $(this).val();
        autocompleteAddedButton.hide();
        if(input === ''){
            autocompleteAddButton.hide();
        }
    });

    if($('#myForm').length) inputSelector.rules('add', {noWhiteSpaceBegin: true});

    var spinner =  createAutoCompleteSearchProgress(inputSelector);

    var autocompleteAddButton = spinner.parent().find('.autocomplete-add-button');

    var autocompleteAddedButton = spinner.parent().find('.autocomplete-added-button');

    inputSelector.on("change", function(){
        spinner.hide();
    });

    inputSelector.autocomplete({
        serviceUrl: route,
        minChars: 1,
        showNoSuggestionNotice: true,
        noSuggestionNotice: notice,
        onSearchStart: function () {
            spinner.show();
            autocompleteAddButton.hide();
            inputSelector.closest("form").find("button[type='submit'],input[type='submit']").prop('disabled', true);
        },
        onSelect: function (suggestion) {
            valueSelector.attr('value', suggestion.data).trigger('change');
            autocompleteAddButton.hide();
        },
        onSearchComplete: function (query, suggestions) {
            console.log(query);
            /*if (saveInputText) {
                if (!suggestions.length) valueSelector.attr('value', query);
            }*/
            if(spinner.length) spinner.hide();
            if (saveInputText) {
                autocompleteAddButton.show().on('click', function (e) {
                    if(e.handled !== true){
                        $(this).hide();
                        var inputValue = inputSelector.val();
                        var filteredArray = $.grep(suggestions, function (item) {
                            return item.value.toLowerCase() === inputValue.toLowerCase();
                        });
                        var data = filteredArray.length ? parseInt(filteredArray[0].data) : inputValue;
                        valueSelector.val(data).trigger('change');
                        e.handled = true;
                        autocompleteAddedButton.show();
                        /*setTimeout(function () {
                            autocompleteAddedButton.hide();
                        },2000);*/
                    }
                })
            }
            inputSelector.closest("form").find("button[type='submit'],input[type='submit']").prop('disabled', false);
        },
        onHide: function (container) {
            /*if (container.context.children[0].className !== 'autocomplete-no-suggestion') {
                valueSelector.attr('value', 0);
                this.value = '';
            }*/
        }
    });
}

function listError(errors){
    var msg = '';
    if(errors.length >= 2){
        for(var i= 0;i < errors.length; i++){
            msg += (i+1+'. '+errors[i]+'\r\n');
        }
    }else{
        msg += errors[0];
    }
    return msg;
}

function limitTextareaText(selector, limit) {
    limit = (typeof limit !== 'undefined') ? limit : 500;
    /*setTimeout(function () {
        $(selector).rules("add", {
            maxlength: limit,
        });
    }, 0);*/

    $(selector).after('<span class="help-block"></span>');
    $(selector).keypress(function (e) {
        var thisTextarea = $(this);
        var tval = thisTextarea.val(),
            tlength = tval.length,
            set = limit,
            remain = parseInt(set - tlength);
        thisTextarea.closest(".form-group").find(".help-block").html('[' + remain + '/' + set + ']');
        if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
            thisTextarea.val((tval).substring(0, tlength - 1))
        }
    });
}

function limitTextareaWord(selector, limit = 30) {
    $(selector).after('<span class="help-block"></span>');
    var helpBlock = $(selector).closest(".form-group").find(".help-block");
    helpBlock.append(`Max words :<strong>${limit}</strong> <span style="color:red"></span>`);
    $(selector).on('keydown', function (e) {
        var words = $.trim(this.value).length ? this.value.split(/,|\s+|,\s+/g).length : 0;
        helpBlock.find('span').html(`[${words}/${limit}]`);
        if (words > limit && e.which !== 8) e.preventDefault();
    });
}

function onNoDataTable() {
    //just in-case the datatable isn't initialized due to no data or the page doesn't have a datatable
    var dt1 = document.getElementsByClassName('datatable').item(0);
    var dt2 = document.getElementsByClassName('data-table').item(0);
    var dt3 = document.getElementById('data-table');
    if (dt1 === null && dt2 === null && dt3 === null) {
        onPageLoaded();
    }
}

function unmaskElement(selector){
    var masks = $(selector).maskMoney('unmasked');
    $(selector).each(function(index,el){
        $(el).val(masks[index]);
    });
}
