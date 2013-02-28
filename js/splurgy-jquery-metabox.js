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
        uncheckedLabel: 'OFF'
    })
    
});


