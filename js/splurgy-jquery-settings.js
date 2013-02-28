jQuery(document).ready(function($) {

    // simpletip
    // $('#settingPageTooltip').simpletip({
    //     content: "Your token can be found in your<br/> <a href='https://offers.splurgy.com/dashboard'>Splurgy Control Panel</a><br/>Click <b>Channel</b> in the navigation bar, and find the channel token that you want the widget to take.",
    //     fixed: true
    // });

    // jconfirmaction
    $('.ask-custom').jConfirmAction({question : "Are you sure this is your token?", yesAnswer : "Yes", cancelAnswer : "Cancel"});

});


