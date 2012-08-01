jQuery(document).ready(function($) {

    // numeric
    $("#offerId").numeric({
        decimal: false,
        negative: false},
        function() {
            alert("Positive integers only");
            this.value = "";
            this.focus();
        }
    )


      var onchange_checkbox = ($('.onchange :checkbox')).iphoneStyle({
        onChange: function(elem, value) {
          $('span#status').html(value.toString());
        }
      });

    // iphone-style-checkboxes
    $(".offerPowerSwitch :checkbox").iphoneStyle({
        checkedLabel: 'ON',
        uncheckedLabel: 'OFF',
        onChange: function() {
            if( $('#offerWrapper').is(':hidden') ) {
                $('#offerWrapper').show()
            } else {
                $('#offerWrapper').hide()
            }


        }
    })

    // simpletip
    $('#postOfferTooltip').simpletip({
        content: "You can find a offer id in your<br/> <a href='https://offers.splurgy.com/dashboard'>Splurgy Control Panel</a><br/>Click <b>Offers</b> in the navigation bar, and find the offer ID of the offer you would like to appear for this post",
        fixed: true
    });
    
    $('#pageOfferTooltip').simpletip({
        content: "Once the switch is turned on it will take the default offer on your channel.",
        fixed: true
    });

});


