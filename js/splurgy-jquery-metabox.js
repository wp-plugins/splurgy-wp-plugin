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
            if( $('#advanced').is(':hidden') ) {
                $('#advanced').show()
            } else {
                $('#advanced').hide()
            }


        }
    })
    

    /* advanced click to open advanced menu */
    $("#advancedPanel").hide();

    $('#advanced a').click(function(event) {
        
        $('#advancedPanel').toggle();
    });

    // simpletip
    $('#postOfferTooltip').simpletip({
        content: "You can find your offer id in the<br/> <a href='http://offers.splurgy.com/campaigns' target='_blank'>Splurgy Campaigns Panel</a>.<br/>",
        fixed: true
    });
    
    $('#pageOfferTooltip').simpletip({
        content: "Turn the switch on and enter the specific offer-id to set a page lock.",
        fixed: true
    });
    
    $('#testmodeq').simpletip({
        content: "Check this box if you'd like to test if page lock will work.",
        fixed: true
    });    

});


