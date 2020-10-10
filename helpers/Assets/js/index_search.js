$(document).ready(function () {
    $(".reload").click(function() {
        $("#w0").find("input").each(function () {
            if($(this).attr("name")=="r"){
                return;
            }
            $(this).val("");
        })
        $("select").empty();
        $("#w0").trigger("submit");
    });
    $("#w0").find("input").each(function () {
        if($(this).attr("type")=="hidden"){
            if($(this).attr("name").indexOf("AutoSearchModle")!==-1){
                $(this).remove();
            }
        }
    })
});