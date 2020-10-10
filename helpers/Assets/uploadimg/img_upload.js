var target="";
$(document).ready(function () {
    var height=$(".img_upload").attr("data-src");
    target=$(".img_upload").attr("data-target");
    $(".img_upload").css("width",height);
    $(".img_upload").css("height",height);
    $(".img_file_container").change(function () {
        change_head(this);
    });
    $(".rel_back").click(function () {
        show_img($(this).attr("src"));
    })
});
function show_img(src) {
    // src="http://bn.yii.com/"+src
    // console.log(src)
    layer.photos({
        photos:{
            "title": "", //相册标题
            "id": 123, //相册id
            "start": 0, //初始显示的图片序号，默认0
            "data": [   //相册包含的图片，数组格式
                {
                    "alt": "图片名",
                    "pid": 666, //图片id
                    "src": src, //原图地址
                    "thumb":""  //缩略图地址
                }
            ]
        }
        ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
    });
}
function change_head(target){
    var img = target.files[0];
    if(img){
        var url = URL.createObjectURL(img);
        var base64 = this.blobToDataURL(img,function(base64Url) {
            // $(target).attr("src",base64Url);
            // $("#img_base64").val(base64Url);
            $(target).parent().parent().find(".rel_back").attr("src",base64Url)
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
function upload_img() {

}