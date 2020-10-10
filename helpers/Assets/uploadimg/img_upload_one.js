// var target="";
// $(document).ready(function () {
//     $(".img_file_container").change(function () {
//         console.log("load")
//         change_head(this);
//     });
//     // $(".rel_back").click(function () {
//     //     show_img($(this).attr("src"));
//     // })
// });
function vue_load() {
    $(".img_file_container").change(function () {
        change_head(this);
    });
    $(".rel_back").click(function () {
        show_img($(this).attr("src"));
    })
}
function change_head(target){
    var img = target.files[0];
    if(img){
        var url = URL.createObjectURL(img);
        var base64 = this.blobToDataURL(img,function(base64Url) {
            // $(target).attr("src",base64Url);
            // $("#img_base64").val(base64Url);
            $(target).parent().find(".rel_back").attr("src",base64Url)
        })
    }
}
function blobToDataURL(blob,cb) {
    let reader = new FileReader();
    reader.onload = function (evt) {
        var base64 = evt.target.result;
        cb(base64)
    };
    reader.readAsDataURL(blob);
}