function upload(target) {
    var img = target.files[0];
    var url=$(target).attr("data-src").split("---")[0];
    var max=$(target).attr("data-src").split("---")[1];
    var name=$(target).attr("data-src").split("---")[2];
    if($(".img_container").find("img-item").length>max){
        layer.msg("超出最多的图片数量，请删除");
        $(target).val("");
        return;
    }
    var formData = new FormData();
    formData.append('Image[img]', img);
    $.ajax({
        url:url,
        type:"post",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.code ==200) {
                var ele=" <div class=\"img-item\">\n" +
                    "                <div class=\"close\" onclick=\"del_item(this)\">" +
                    "<span class=\"glyphicon glyphicon-remove\"></span></div>\n" +
                    "                <img src=\"&src&\" alt=\"\">\n" +
                    "                <input type=\"hidden\" value='&src1&' name=\"&name&\" ?>\n" +
                    "            </div>";
                ele=ele.replace("&src&",data.url).replace("&name&",name).replace("&src1&",data.url);
                $("#img-container-item").append(ele);
            } else {
                layer.msg("上传失败");
            }
        },
        error:function(data) {
            layer.msg("系统异常");
        }
    });
}
function del_item(target) {
    $(target).parent().remove();
}
function show_big(target) {
    show_img($(target).attr(("src")))
}
function show_img(src) {
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