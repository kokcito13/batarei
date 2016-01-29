$(function(){
    $('div#firstAdd').insertAfter( $('div.article p').find('img').first() );
    setTimeout(function(){
        var a = document.createElement('script');
        var m = document.getElementsByTagName('head')[0];
        a.async = true;
        //a.defer = 1;
        a.src = '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
        m.appendChild(a);
    },2000);

    var issetOfferText = false;
    setTimeout(function() {
        if (!issetOfferText) {
            issetOfferText = true;
            $.getJSON(getTextOffer)
                .done(function (data) {
                    if (data.success) {
                        if (data.data.shortText && data.data.shortText.length > 100) {
                            $('#offerShortText').html(data.data.shortText);
                            $('#offerShortText').show();
                        }
                        if (data.data.text && data.data.text.length > 100) {
                            $('#offerText').html(data.data.text);
                            $('#offerText').show();
                        }
                        if (data.data.firstTextBlock && data.data.firstTextBlock.length > 100) {
                            $('#offerBlockText').html(data.data.firstTextBlock);
                            $('#offerBlockText').show();
                        }
                    } else
                        console.log(data);
                });
        }
    }, 2500);
});