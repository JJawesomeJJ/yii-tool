<?php
use dsj\pick\assets\ScottAsset;
\dsj\components\assets\LayuiAsset::register($this);
ScottAsset::register($this);
$webPath = Yii::getAlias('@web');
$url = yii\helpers\Url::to(['/pick/scott/index']);

$css = <<<CSS
#map_window{
    height: 900px;
}
.map_header{
    height: 50px;
    /*margin-top: 10px;*/
    /*margin-left: 10px;*/
    /*margin-right: 20px;*/
}
.map_content{
    margin-top: 10px;
    height: {$layer_height};
    width: {$layer_weight};
}
.map_contain{
    overflow: hidden;
}

div.amap-sug-result{
    z-index: 100000000;
}
CSS;
$this->registerCss($css);
$js = <<<JS
 layui.config({
			base: '$webPath/layui/src/layuiadmin/'
		}).use(['layer','form'], function(){
       var layer = layui.layer;
       var form = layui.form;
       var type = 0;
       var res = [];
         form.render();
         form.on('select', function (data) {
            type = data.value
         });

      $(".{$btnClass}").on('click',function() {
              layer.open({
                  type: 1, 
                  shadeClose: true,
                  title: '坐标选取',
                  scrollbar:true,
                  area: ['{$layer_weight}','{$layer_height}'],
                  content: $('#map_window'),
                  success: function(layero, index){
                       $('#map_window').attr('style','display: block')
                        //地图加载
                        var map = new AMap.Map("scott_map", {
                            resizeEnable: true
                        });
                        
                         //输入提示
                        var autoOptions = {
                            input: "tipinput"
                        };
                        var auto = new AMap.Autocomplete(autoOptions);
                        var placeSearch = new AMap.PlaceSearch({
                            map: map
                        });
                        //构造地点查询类
                        //注册监听，当选中某条记录时会触发
                        AMap.event.addListener(auto, "select", select);
                        function select(e) {
                            placeSearch.setCity(e.poi.adcode);
                            //关键字查询查询
                            placeSearch.search(e.poi.name);
                        }
                        //为地图注册click事件获取鼠标点击出的经纬度坐标
                        map.on('click', function(e) {
                            //GCJ02坐标系
                            var GCJ02Lng = e.lnglat.getLng();
                            var GCJ02Lat = e.lnglat.getLat();
                            //WGS84坐标系
                            var WGS84Arr = gcj02towgs84(e.lnglat.getLng(),e.lnglat.getLat());
                            var WGS84Lng = WGS84Arr[0];
                            var WGS84Lat = WGS84Arr[1];
                            //BD09坐标系
                            var BD09Arr = gcj02tobd09(e.lnglat.getLng(),e.lnglat.getLat());
                            var BD09Lng = BD09Arr[0];
                            var BD09Lat = BD09Arr[1];
                            
                            res = [GCJ02Lng+','+GCJ02Lat,WGS84Lng+','+WGS84Lat,BD09Lng+','+BD09Lat];
                            
                            $('#GCJ02').val(e.lnglat.getLng() + ',' + e.lnglat.getLat())
                        });
                  },
                  btn: ['确认选取'],
                  btn1: function(index){
                    $('#coor-input').val(res[type]);
                     layer.close(layer.index);
                     $('#map_window').attr('style','display: none')
                 },
              });
              
      });
      //坐标转换方法
      var x_PI = 3.14159265358979324 * 3000.0 / 180.0;
      var PI = 3.1415926535897932384626;
      var a = 6378245.0;
      var ee = 0.00669342162296594323;
     //火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换
     function gcj02tobd09(lng, lat) {
        var z = Math.sqrt(lng * lng + lat * lat) + 0.00002 * Math.sin(lat * x_PI);
        var theta = Math.atan2(lat, lng) + 0.000003 * Math.cos(lng * x_PI);
        var bd_lng = z * Math.cos(theta) + 0.0065;
        var bd_lat = z * Math.sin(theta) + 0.006;
        return [bd_lng, bd_lat]
     }
     //GCJ02 转换为 WGS84
     function gcj02towgs84(lng, lat) {
        if (out_of_china(lng, lat)) {
        return [lng, lat]
        }
        else {
            var dlat = transformlat(lng - 105.0, lat - 35.0);
            var dlng = transformlng(lng - 105.0, lat - 35.0);
            var radlat = lat / 180.0 * PI;
            var magic = Math.sin(radlat);
            magic = 1 - ee * magic * magic;
            var sqrtmagic = Math.sqrt(magic);
            dlat = (dlat * 180.0) / ((a * (1 - ee)) / (magic * sqrtmagic) * PI);
            dlng = (dlng * 180.0) / (a / sqrtmagic * Math.cos(radlat) * PI);
            mglat = lat + dlat;
            mglng = lng + dlng;
            return [lng * 2 - mglng, lat * 2 - mglat]
        }
    }
    function transformlat(lng, lat) {
        var ret = -100.0 + 2.0 * lng + 3.0 * lat + 0.2 * lat * lat + 0.1 * lng * lat + 0.2 * Math.sqrt(Math.abs(lng));
        ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(lat * PI) + 40.0 * Math.sin(lat / 3.0 * PI)) * 2.0 / 3.0;
        ret += (160.0 * Math.sin(lat / 12.0 * PI) + 320 * Math.sin(lat * PI / 30.0)) * 2.0 / 3.0;
        return ret
    }
    function transformlng(lng, lat) {
        var ret = 300.0 + lng + 2.0 * lat + 0.1 * lng * lng + 0.1 * lng * lat + 0.1 * Math.sqrt(Math.abs(lng));
        ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
        ret += (20.0 * Math.sin(lng * PI) + 40.0 * Math.sin(lng / 3.0 * PI)) * 2.0 / 3.0;
        ret += (150.0 * Math.sin(lng / 12.0 * PI) + 300.0 * Math.sin(lng / 30.0 * PI)) * 2.0 / 3.0;
        return ret
    }
    //判断是否在国内，不在国内则不做偏移
    function out_of_china(lng, lat) {
        return (lng < 72.004 || lng > 137.8347) || ((lat < 0.8293 || lat > 55.8271) || false);
    }
});              
JS;
$this->registerJs($js);
?>

<div id="map_window" style="display: none">
    <div class="map_contain">
        <div class="map_header">
            <div class="layui-row layui-col-space6">
                <div class="layui-col-md4">
                    <input type="text" name="title" id="tipinput" placeholder="请输入..." autocomplete="off" class="layui-input">
                </div>
                <div class="layui-col-md4">
                    <div class="layui-inline">
                        <label class="layui-form-label">GCJ02</label>
                        <div class="layui-input-inline" style="width: 200px;">
                            <input type="text" name="" autocomplete="off" id="GCJ02" class="layui-input">
                        </div>
                    </div>
                </div>

                <div class="layui-col-md4">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label">转换</label>
                            <div class="layui-input-block">
                                <select name="type">
                                    <option value="0">GCJ02坐标系</option>
                                    <option value="1">WGS84坐标系</option>
                                    <option value="2">BD09坐标系</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="description">
            <blockquote class="layui-elem-quote">
                WGS84坐标系:地球坐标系，国际通用坐标系;
                GCJ02坐标系:火星坐标系，WGS84坐标系加密后的坐标系；Google国内地图、高德、QQ地图 使用;
                BD09坐标系:百度坐标系，GCJ02坐标系加密后的坐标系。
            </blockquote>
        </div>
        <div class="map_content" id="scott_map"></div>
    </div>
</div>



