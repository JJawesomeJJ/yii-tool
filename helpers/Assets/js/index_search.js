$(document).ready(function () {
    setTimeout(function () {
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
    },20);

    $("#w0").find("input").each(function () {
        if($(this).attr("type")=="hidden"){
            if($(this).attr("name").indexOf("AutoSearchModle")!==-1){
                $(this).remove();
            }
        }
    })
});