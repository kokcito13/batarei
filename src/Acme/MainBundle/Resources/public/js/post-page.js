$(function(){
    var issetOfferText = false;
    setTimeout(function() {
        if (!issetOfferText) {
            issetOfferText = true;
            $.getJSON(getTextOffer)
                .done(function (data) {
                    if (data.success) {
                        if (data.data.shortText && data.data.shortText.length > 100) {
                            $('#offerShortText').html(data.data.shortText);

                            $('div#offerShortText > ul').addClass('list mark-circle');
                            $('div#offerShortText > ol').addClass('list mark-circle-numbers');
                        }
                        if (data.data.text && data.data.text.length > 100) {
                            $('#offerText').html(data.data.text);

                            $('div#offerText > ul').addClass('list mark-circle');
                            $('div#offerText > ol').addClass('list mark-circle-numbers');
                        }
                    } else
                        console.log(data);
                });
        }
    }, 1500);
});