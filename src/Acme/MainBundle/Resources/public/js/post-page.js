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
                        }
                        if (data.data.text && data.data.text.length > 100) {
                            $('#offerText').html(data.data.text);
                        }
                    } else
                        console.log(data);
                });
        }
    }, 2500);
});