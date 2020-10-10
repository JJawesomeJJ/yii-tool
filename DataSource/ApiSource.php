<?php


namespace backend\modules\tool\DataSource;


abstract class ApiSource extends DataAdapter
{
    protected $requestMethod="GET";//约定接口的访问访问方式
    protected $requestUrl="";
    protected $current_page=1;
    protected $request_params;
    protected $request_headers=[];
    abstract function product_url():array;//生产URL 以及配置参数
    protected function DataSource()
    {
        // TODO: Implement DataSource() method.
    }
    protected function StoreData($data)
    {
        // TODO: Implement StoreData() method.
    }
    protected function HandleData($data)
    {
        // TODO: Implement HandleData() method.
    }
}