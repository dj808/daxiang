<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 公共函数
//-------------------------

use think\Session;
use think\Response;
use think\Request;
use think\Url;

/**
 * CURLFILE 兼容性处理 php < 5.5
 * 一定不要修改、删除，否则 curl 可能无法上传文件
 */
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        . ($postname ?: basename($filename))
        . ($mimetype ? ";type=$mimetype" : '');
    }
}

/**
 * flash message
 *
 * flash("?KEY") 判断是否存在flash message KEY 返回bool值
 * flash("KEY") 获取flash message，存在返回具体值，不存在返回null
 * flash("KEY","VALUE") 设置flash message
 * @param string $key
 * @param bool|string $value
 * @return bool|mixed|null
 */
function flash($key, $value = false)
{
    $prefix = 'flash_';
    // 判断是否存在flash message
    if ('?' == substr($key, 0, 1)) {
        return Session::has($prefix . substr($key, 1));
    } else {
        $flash_key = $prefix . $key;
        if (false === $value) {
            // 获取flash
            $ret = Session::pull($flash_key);

            return null === $ret ? null : unserialize($ret);
        } else {
            // 设置flash
            return Session::set($flash_key, serialize($value));
        }
    }
}

/**
 * 表格排序筛选
 * @param string $name  单元格名称
 * @param string $field 排序字段
 * @return string
 */
function sort_by($name, $field = '')
{
    $sort = Request::instance()->param('_sort');
    $param = Request::instance()->get();
    $param['_sort'] = ($sort == 'asc' ? 'desc' : 'asc');
    $param['_order'] = $field;
    $url = Url::build(Request::instance()->action(), $param);

    return Request::instance()->param('_order') == $field ?
        "<a href='{$url}' title='点击排序' class='sorting-box sorting-{$sort}'>{$name}</a>" :
        "<a href='{$url}' title='点击排序' class='sorting-box sorting'>{$name}</a>";
}

/**
 * 用于高亮搜索关键词
 * @param string $string 原文本
 * @param string $needle 关键词
 * @param string $class  span标签class名
 * @return mixed
 */
function high_light($string, $needle = '', $class = 'c-red')
{
    return $needle !== '' ? str_replace($needle, "<span class='{$class}'>" . $needle . "</span>", $string) : $string;
}

/**
 * 用于显示状态操作按钮
 * @param int $status        0|1|-1状态
 * @param int $id            对象id
 * @param string $field      字段，默认id
 * @param string $controller 默认当前控制器
 * @return string
 */
function show_status($status, $id, $field = 'id', $controller = '')
{
    $controller === '' && $controller = Request::instance()->controller();
    switch ($status) {
        // 恢复
        case 0 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/resume', [$field => $id]) . '\',{},change_status,[this,\'resume\'])" class="label label-success radius" title="点击恢复">恢复</a>';
            break;
        // 禁用
        case 1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/forbid', [$field => $id]) . '\',{},change_status,[this,\'forbid\'])" class="label label-warning radius" title="点击禁用">禁用</a>';
            break;
        // 还原
        case -1 :
            $ret = '<a href="javascript:;" onclick="ajax_req(\'' . Url::build($controller . '/recycle', [$field => $id]) . '\')" class="label label-secondary radius" title="点击还原">还原</a>';
            break;
    }

    return $ret;
}

/**
 * 显示状态
 * @param int $status     0|1|-1
 * @param bool $imageShow true只显示图标|false只显示文字
 * @return string
 */
function get_status($status, $imageShow = true)
{
    switch ($status) {
        case 0 :
            $showText = '禁用';
            $showImg = '<i class="Hui-iconfont c-warning status" title="禁用">&#xe631;</i>';
            break;
        case -1 :
            $showText = '删除';
            $showImg = '<i class="Hui-iconfont c-danger status" title="删除">&#xe6e2;</i>';
            break;
        case 1 :
        default :
            $showText = '正常';
            $showImg = '<i class="Hui-iconfont c-success status" title="正常">&#xe615;</i>';

    }

    return ($imageShow === true) ? $showImg : $showText;
}

/**
 * 框架内部默认ajax返回
 * @param string $msg      提示信息
 * @param string $redirect 重定向类型 current|parent|''
 * @param string $alert    父层弹框信息
 * @param bool $close      是否关闭当前层
 * @param string $url      重定向地址
 * @param string $data     附加数据
 * @param int $code        错误码
 * @param array $extend    扩展数据
 */
function ajax_return_adv($msg = '操作成功', $redirect = 'parent', $alert = '', $close = false, $url = '', $data = '', $code = 0, $extend = [])
{
    $extend['opt'] = [
        'alert'    => $alert,
        'close'    => $close,
        'redirect' => $redirect,
        'url'      => $url,
    ];

    return ajax_return($data, $msg, $code, $extend);
}

/**
 * 返回错误json信息
 */
function ajax_return_adv_error($msg = '', $code = 1, $redirect = '', $alert = '', $close = false, $url = '', $data = '', $extend = [])
{
    return ajax_return_adv($msg, $alert, $close, $redirect, $url, $data, $code, $extend);
}

/**
 * ajax数据返回，规范格式
 * @param array $data   返回的数据，默认空数组
 * @param string $msg   信息
 * @param int $code     错误码，0-未出现错误|其他出现错误
 * @param array $extend 扩展数据
 */
function ajax_return($data = [], $msg = "", $code = 0, $extend = [])
{
    $ret = ["code" => $code, "msg" => $msg, "data" => $data];
    $ret = array_merge($ret, $extend);

    return Response::create($ret, 'json');
}

/**
 * 返回标准错误json信息
 */
function ajax_return_error($msg = "出现错误", $code = 1, $data = [], $extend = [])
{
    return ajax_return($data, $msg, $code, $extend);
}

/**
 * 从二维数组中取出自己要的KEY值
 * @param  array $arrData
 * @param string $key
 * @param $im true 返回逗号分隔
 * @return array
 */
function filter_value($arrData, $key, $im = false)
{
    $re = [];
    foreach ($arrData as $k => $v) {
        if (isset($v[$key])) $re[] = $v[$key];
    }
    if (!empty($re)) {
        $re = array_flip(array_flip($re));
        sort($re);
    }

    return $im ? implode(',', $re) : $re;
}

/**
 * 重设键，转为array(key=>array())
 * @param array $arr
 * @param string $key
 * @return array
 */
function reset_by_key($arr, $key)
{
    $re = [];
    foreach ($arr as $v) {
        $re[$v[$key]] = $v;
    }

    return $re;
}

/**
 * 节点遍历
 *
 * @param        $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int    $root
 *
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = [];
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = [];
        foreach ($list as $key => $data) {
            if ($data instanceof \think\Model) {
                $list[$key] = $data->toArray();
            }
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            if (!isset($list[$key][$child])) {
                $list[$key][$child] = [];
            }
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }

    return $tree;
}

/**
 * 统一密码加密方式，如需变动直接修改此处
 * @param $password
 * @return string
 */
function password_hash_tp($password)
{
    return hash("md5", trim($password));
}

/**
 * 生成随机字符串
 * @param string $prefix
 * @return string
 */
function get_random($prefix = '')
{
    return $prefix . base_convert(time() * 1000, 10, 36) . "_" . base_convert(microtime(), 10, 36) . uniqid();
}

/**
 * 获取自定义配置
 * @param string|int $name 配置项的key或者value，传key返回value，传value返回key
 * @param string $conf
 * @param bool $key        传递的是否是配置键名，默认是，则返回配置信息
 * @return int|string
 */
function get_conf($name, $conf, $key = true)
{
    $arr = config("conf." . $conf);
    if ($key) return $arr[$name];
    foreach ($arr as $k => $v) {
        if ($v == $name) {
            return $k;
        }
    }
}


/**
 * 多维数组合并（支持多数组）
 * @return array
 */
function array_merge_multi()
{
    $args = func_get_args();
    $array = [];
    foreach ($args as $arg) {
        if (is_array($arg)) {
            foreach ($arg as $k => $v) {
                if (is_array($v)) {
                    $array[$k] = isset($array[$k]) ? $array[$k] : [];
                    $array[$k] = array_merge_multi($array[$k], $v);
                } else {
                    $array[$k] = $v;
                }
            }
        }
    }

    return $array;
}


/**
 * 将list_to_tree的树还原成列表
 * @param array $tree
 * @param string $child
 * @param string $order
 * @param int $level
 * @param null $filter
 * @param array $list
 * @return array
 */
function tree_to_list($tree, $filter = null, $child = '_child', $order = 'id', $level = 0, &$list = [])
{
    if (is_array($tree)) {
        if (!is_callable($filter)) {
            $filter = function (&$refer, $level) {
                $refer['level'] = $level;
            };
        }
        foreach ($tree as $key => $value) {
            $refer = $value;
            unset($refer[$child]);
            $filter($refer, $level);
            $list[] = $refer;
            if (isset($value[$child])) {
                tree_to_list($value[$child], $filter, $child, $order, $level + 1, $list);
            }
        }
    }

    return $list;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list   查询结果
 * @param string $field 排序的字段名
 * @param array $sortBy 排序类型
 *                      asc正向排序 desc逆向排序 nat自然排序
 * @return array|bool
 */
function list_sort_by($list, $field, $sortBy = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = [];
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortBy) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];

        return $resultSet;
    }

    return false;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;

    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 生成一定长度的UUID
 *
 * @param int $length
 *
 * @return string
 */
function get_uuid($length = 16)
{
    mt_srand((double)microtime()*10000);
    $uuid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    $str = base64_encode($uuid);
    return substr($str,  mt_rand(0, strlen($str) - $length), $length);
}

/**
 * 根据模型名称获取模型
 *
 * @param $modelName
 *
 * @return \think\Model|\think\db\Query
 */
function get_model($modelName)
{
    if (false !== strpos($modelName, '\\')) {
        // 指定模型类
        $db = new $modelName;
    } else {
        try {
            $db = \think\Loader::model($modelName);
        } catch (\think\exception\ClassNotFoundException $e) {
            $db = \think\Db::name($modelName);
        }
    }

    return $db;
}

/**
 * 验证规则扩展
 */
\think\Validate::extend([
    // 验证字段是否在模型中存在
    'checkExist' => function($value, $rule, $data, $field) {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        $db = get_model($rule[0]);
        $key = isset($rule[1]) ? $rule[1] : $field;

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                $map[$key] = $data[$key];
            }
        } elseif (strpos($key, '=')) {
            parse_str($key, $map);
        } else {
            $map[$key] = $data[$field];
        }

        $pk = strval(isset($rule[3]) ? $rule[3] : $db->getPk());
        if (isset($rule[2])) {
            $map[$pk] = ['neq', $rule[2]];
        } elseif (isset($data[$pk])) {
            $map[$pk] = ['neq', $data[$pk]];
        }

        if ($db->where($map)->field($pk)->find()) {
            return true;
        }
        return false;
    }
]);
//提现
/**  
    * 获取签名 
    * @param array $arr
    * @return string
    */  
    function getSign($arr){
        //去除空值
        $arr = array_filter($arr);
        if(isset($arr['sign'])){
            unset($arr['sign']);
        }
        //按照键名字典排序
        ksort($arr);
        //生成url格式的字符串
       $str = $this->arrToUrl($arr) . '&key=' . 'abc123456def789ghijklmn123456766';
       return strtoupper(md5($str));
    }
    /**  
    * 获取带签名的数组 
    * @param array $arr
    * @return array
    */  
    function setSign($arr){
        $arr['sign'] = $this->getSign($arr);;
        return $arr;
    }
    /**  
    * 数组转URL格式的字符串
    * @param array $arr
    * @return string
    */
    function arrToUrl($arr){
        return urldecode(http_build_query($arr));
    }
    
    //数组转xml
    function ArrToXml($arr)
    {
            if(!is_array($arr) || count($arr) == 0) return '';

            $xml = "<xml>";
            foreach ($arr as $key=>$val)
            {
                    if (is_numeric($val)){
                            $xml.="<".$key.">".$val."</".$key.">";
                    }else{
                            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                    }
            }
            $xml.="</xml>";
            return $xml; 
    }
    
    //Xml转数组
    function XmlToArr($xml)
    {
       //print_r($xml);die;
            if($xml == '') return '';
            libxml_disable_entity_loader(true);
            $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
          //  print_r($arr);die;
            return $arr;
    }




    function postData($url,$postfields){
       // print_r($postfields);die;
        // $content = getcwd().'/apiclient_cert.pem';
        // file_put_contents('111.text',$content);die;
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;
        //以下是证书相关代码
        $params[CURLOPT_SSLCERTTYPE] = 'PEM';
        $params[CURLOPT_SSLCERT] = getcwd().'/apiclient_cert.pem';
        $params[CURLOPT_SSLKEYTYPE] = 'PEM';
        $params[CURLOPT_SSLKEY] = getcwd().'/apiclient_key.pem';

        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
       //file_put_contents('111.text',$content);
        curl_close($ch); //关闭连接\
          return $content;
    }
//提现结束
// ---- 以下是微信现金红包的区域start ---- //
//数组转xml
function arrayToXml($arr){
    $xml = "<xml>";
    foreach ($arr as $key=>$val){
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">"; 
        }
    }
    $xml.="</xml>";
    return $xml;
}    
/**
 * 微信发放现金红包核心函数，调用本函数就直接发放红包了。
 * @param $url 现金红包的请求地址
 * @param $obj
 * @return mixed
 */
function pay_lucky_money($url, $obj)
{
    //创建随机字符串(32位)
    $obj['nonce_str'] = str_rand();
    //创建签名
    $sign = get_sign($obj, false);
    //halt($sign);
    $obj['sign'] = $sign;    //将签名传入数组
    $postXml = arrayToXml($obj);    //将参数转为xml格式
    //halt($postXml);
    $responseXml = curl_post_ssl($url, $postXml);    //提交请求
    //halt($responseXml);
    return $responseXml;
}
function str_rand($length = 32)
    {
        
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
/**
 * @param $arr 生成前面的参数
 * @param $urlencode
 * @return string 返回加密后的签名
 */
function get_sign($arr, $urlencode)
{
    $buff = "";
    //对传进来的数组参数里面的内容按照字母顺序排序，a在前面，z在最后（字典序）
    ksort($arr);
    foreach ($arr as $k => $v) {
        if (null != $v && "null" != $v && "sign" != $k) {    //签名不要转码
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
    }
    // 去掉末尾符号“&”，其实不用这个if，因为长度肯定大于0
    if (strlen($buff) > 0) {
        $stringA = substr($buff, 0, strlen($buff) - 1);
    }
    //签名拼接api
    $stringSignTemp = $stringA . "&key=" . config('wx_sh.key');
    //签名加密并大写
    $sign = strtoupper(md5($stringSignTemp));
    return $sign;
}


//post请求网站，需要证书
function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
{
    $ch = curl_init();
    //超时时间
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //cert 与 key 分别属于两个.pem文件
    //请确保您的libcurl版本是否支持双向认证，版本高于7.20.1，相当于发curl验证【当前文件所在目录/cert/wxpay/】下的两个pem证书文件。
    curl_setopt($ch, CURLOPT_SSLCERT, dirname(__FILE__) . DIRECTORY_SEPARATOR .
        'cert' . DIRECTORY_SEPARATOR . 'wxpay' . DIRECTORY_SEPARATOR . 'apiclient_cert.pem');
    curl_setopt($ch, CURLOPT_SSLKEY, dirname(__FILE__) . DIRECTORY_SEPARATOR .
        'cert' . DIRECTORY_SEPARATOR . 'wxpay' . DIRECTORY_SEPARATOR . 'apiclient_key.pem');
    //curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).DIRECTORY_SEPARATOR.
    //    'cert'.DIRECTORY_SEPARATOR.'rootca.pem');    //这个不需要，因为大部分的操作系统都已经内置了rootca.pem证书了，就是常见的CA证书。
    if (count($aHeader) >= 1) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    $data = curl_exec($ch);
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        echo "call faild, errorCode:$error\n";
        curl_close($ch);
        return false;
    }
}
// ---- 以下是微信现金红包的区域end ---- //
