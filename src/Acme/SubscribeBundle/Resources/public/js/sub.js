var sub = {
    elForm: 'form.subscribe',
    elButton: 'button.save',
    init: function(){
        $(sub.elForm+' '+sub.elButton).click(function(){
            sub.submitForm($(this).parent().parent().parent());

            return false;
        });
        $(sub.elForm+' input[name=email]').keyup(function(){
            sub.validateEmail($(this));
        });
        $(sub.elForm).submit(function(){
            sub.submitForm($(this));

            return false;
        });
    },
    validateFrom: function(form)
    {
        var result = true;
        var email = form.find('input[name=email]').val().trim();
        if (!sub.validateInput(email)) {
            result = false;
        }

        return result;
    },
    submitForm: function(form)
    {
        form.find('div.note-error').hide();

        if (sub.validateFrom(form)) {
            var email = form.find('input[name=email]').val().trim();
            $.ajax({
                url: form.attr("action"),
                data: {email: email},
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (typeof data.success != "undefined" && data.success) {
                        $(sub.elButton).hide();
                        form.find('div.note-success').html('Вы успешно подписались');
                        form.find('div.note-success').show();
                    }
                }
            });
        } else {
            form.find('div.note-error').html('E-mail указан не корректно');
            form.find('div.note-error').show();
        }

        return false;
    },
    save: function()
    {

    },
    validateEmail: function (input)
    {
        var label = input.parent('label');
        var email = input.val().trim();
        var result = sub.validateInput(email);

        label.removeClass('state-error');
        if (!result) {
            label.addClass('state-error');
        }

        return false;
    },
    validateInput: function(email)
    {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
};
$(document).ready(function() {
    sub.init();
});