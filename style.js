$(document).ready(function(){
    $("#overview").show();
    $("#setup").hide();
    $("#misc").hide();
    $("#btn_overview").addClass("button-active");

    $(".button").click(function(){
        $('[id*=btn').removeClass("button-active");
        $(this).addClass("button-active");
        switch($(this).attr('id')) {
            case "btn_overview":
                $("#overview").show();
                $("#setup").hide();
                $("#misc").hide();
                break;
            case "btn_setup":
                $("#overview").hide();
                $("#setup").show();
                $("#misc").hide();
                break;
            case "btn_misc":
                $("#overview").hide();
                $("#setup").hide();
                $("#misc").show();
                break;
        }
    });
});