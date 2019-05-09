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
// 首页
//-------------------------

namespace app\index\controller;

use app\index\Controller;
use think\Loader;
use think\Session;
use think\Db;
use think\Image;
class Index extends Controller
{
    
    public function login(){
        $uid=$this->request->param('id');
        session('userpid',$uid);
        $s="http://daxiang.meyatu.com/index.php/index/index/index";           
        header("Location:".$s."");exit;
    }

    public function index()
    {
            if ($this->request->param('code')==''){//没有code，去微信接口获取code码

                $this->getcodess();
                file_put_contents("log322.log",2);
            } else {
                //获取code后跳转回来到这里了
                $code=$this->request->param('code');
                //获取openid
                $openid = $this->getopenid($code);//获取openid对应的值
                // dump($_SESSION);exit;
                $userinfo = $this->getUserInfo(session('opennewid'),session('newaccess_token'));
                // 取出用户信息                
                /*
                *user_openid :用户openId
                *user_nickname :用户昵称    
                *user_headimgurl :用户头像url
                */
                // print_r($userinfo);die;
                $user_openid = $userinfo['openid'];

                $user_nickname = $userinfo['nickname'];       

                $user_headimgurl = $userinfo['headimgurl'];
                session('useropenid',$user_openid);
                // 判断用户是否存在
                $data_user = Db::name('bminfo')->where('open_id',$user_openid)->find();
                $this->view->assign('data_user',$data_user['id']);
                if(empty($data_user)){

                    $info['client_id']=$_SERVER['REMOTE_ADDR'];

                    $info['open_id']=$user_openid;

                    $info['name']=$user_nickname;
                    // if(session('userpid')){
                    //     $info['pid']=session('userpid');
                    // }

                    $info['image']=$user_headimgurl;
                    $info['create_time']=date('Y-m-d H:i:s',time());

                    if($this->request->param('id')){

                        $info['pid']=$this->request->param('id');

                    }
                    // 新增用户到数据库                    
                    $new_user = Db::name('bminfo')->insertGetId($info);                                         
                    $this->view->assign('data_user',$new_user);
                }
                                    
            }               
            //得到活动
            $act=Db::name('activity')->where('status',1)->find();
            if($act){
                session('actid',$act['id']);
                $act['editorValue']=htmlspecialchars_decode($act['editorValue']);
                //年月日
                $date=strtotime($act['end_time']);
                $year=date('Y',$date);
                $month=date('m',$date);
                $day=date('d',$date);
                
                $act['year']=$year;
                $act['month']=$month;
                $act['day']=$day;
                $this->view->assign('act',$act);
                // if(session('userpid')){
                //     $info['pid']=session('userpid');
                //     $us['uid']=session('userpid');
                //     $us['actid']=$act['id'];
                //     $sh=Db::name('user_activity_share')->where($us)->find();
                //     if($sh){
                //         Db::name('user_activity_share')->insert($us);  
                //     }
                    
                // } 
                $action=explode('，',$act['action']);  
                
                $this->view->assign('action',$action);
                
                
                // dump($act);exit;
                // dump($act);exit;
                //得到参与人(分享)
                $wu['actid']=$act['id'];
                $share=Db::name('user_activity_share')->field('uid')->where($wu)->select();
                foreach ($share as $v){
                  $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
                  $temp[]=$v;
                 }
                 $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
                 foreach ($temp as $k => $v){
                  $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
                 }
                 $sharenew=$temp;
                // dump($sharenew);exit;

                
                $sharenum=count($sharenew);
                $this->view->assign('sharenum',$sharenum);

                $user=array();
                foreach($sharenew as $kk=>$v){
                    $user[]=Db::name('bminfo')->field('id,name,image')->where('id',$v[0])->find();
                }
                $this->view->assign('user',$user);
                //得到购买人
                $wb['actid']=$act['id'];
                $buy=Db::name('user_activity')->field('uid')->where($wb)->select();
                $buynum=count($buy);
                $this->view->assign('buynum',$buynum);
                $buyuser=array();
                foreach($buy as $v){
                    $buyuser[]=Db::name('bminfo')->field('id,phone,name,create_time,image')->where('id',$v['uid'])->find();
                }
                $this->view->assign('buyuser',$buyuser);
                //赚钱排行榜
                $zu=Db::name('bminfo')->field('id,name,yongjin')->order('yongjin desc')->select();
                foreach($zu as $kk=>$v){
                    $xu=Db::name('bminfo')->where('pid',$v['id'])->select();
                    $zu[$kk]['count']=count($xu);
                }
                $this->view->assign('zu',$zu);
            }else{
                $this->assign('act','');
                $this->assign('user','');
                $this->assign('buyuser','');
                $this->assign('zu','');
            }
        
        
            return $this->view->fetch();

    }
    //获取用户信息
    public function getUserInfo($openid,$access_token)
    {

        // dump($openid);
        // dump($access_token);exit;
        //获取access_token
        // $access_token = $this->get_Accesstoken();//获取access_token对应的值

        //获取openid
        // $openid = $access_token_array['openid'];//获取openid对应的值
        //$open_id = $openid['openid'];//获取openid对应的值
        //$access_token = $openid['access_token'];//获取openid对应的值
        // $userinfo_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        // $userinfo_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=17_0XXBg4Uhjqkr2NO3FmOdK2SKGdtbr50XGrgRYPZwp1o_1OD8vn7lpvAeEFLsSvTQEB7et6-WxHf5lxFyH7hSRbY6XNH8uLrt9eNMeAq3FZOFYFnlSjLB1_1DMD8j2MmMVPP5XT9FvYHSbOFbZVAjAJAPLQ&openid=$openid&lang=zh_CN";
        // $userinfo_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token==&openid=$open_id&lang=zh_CN";
        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        curl_setopt($ch, CURLOPT_URL, $userinfo_url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,false);
        $result = curl_exec($ch); 
        curl_close($ch);
        file_put_contents("logsa.txt", $result);
        $info = json_decode(htmlspecialchars_decode($result), true); 
        // $userinfo_json = $this->https_request($userinfo_url);
        // $userinfo_array = json_decode($userinfo_json,true);
        // dump($info);exit;
        return $info;
    }

    public function https_request($url)//自定义函数,访问url返回结果
    {
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       
        $data = curl_exec($curl);
       
        
        if (curl_errno($curl)){
            return 'ERROR'.curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }
    
    
    //授权反馈
    public function getcodess()
    {
        file_put_contents("log3233.log",1);
        $url = "http://daxiang.meyatu.com/index/index/index";
        $url = urlencode($url);

        $s="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxafe3f25daebca711&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        // $s="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxafe3f25daebca711&redirect_uri=$url&response_type=code&scope=SCOPE&state=STATE#wechat_redirect";
        header("Location:".$s."");exit;
    }
    //获取access_token
    public function get_Accesstoken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxafe3f25daebca711&secret=567b6d7ff310bc2bc8793e6401dbdad7"; 
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,false);
        $result = curl_exec($ch);  
        curl_close($ch);
        file_put_contents("log.txt", $result);
        $access_token = json_decode(htmlspecialchars_decode($result), true);

        return $access_token['access_token'];
        
        
    }

    //获取openid
    public function getopenid($code)
    {
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxafe3f25daebca711&secret=567b6d7ff310bc2bc8793e6401dbdad7&code=$code&grant_type=authorization_code";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo=curl_exec($curl);
        curl_close($curl);
        file_put_contents("log32.log",$tmpInfo,FILE_APPEND);
        
        
       
        $openid = json_decode(htmlspecialchars_decode($tmpInfo), true);
        
        if(isset($openid['openid'])){
            session('opennewid',$openid['openid']);
            session('newaccess_token',$openid['access_token']);
            return $openid;
        }else{
            $newopenid['openid']=session('opennewid');
            $newopenid['access_token']=session('newaccess_token');
            return $newopenid;
        }
        
        // return $openid;
       
       
                
    }

    
   
    //优惠券
    public function coupon(){

        $coupon=Db::name('coupon')->where('uid',1)->select();
       
        $this->view->assign('coupon',$coupon);
        
        return $this->view->fetch();

    }
     //立即报名
    public function enrol(){
       
         $this->view->assign('id',$this->request->param('id'));
         $this->view->assign('actid',$this->request->param('actid'));
        return $this->view->fetch();

    }

    //赚红包（点击这个按钮，传递用户id）
    public function share(){
        $id=$this->request->param('id');
        $act=Db::name('activity')->where('status',1)->find();
        $this->view->assign('act',$act);
        $data=Db::name('bminfo')->field('id,qrcode')->where('id',$id)->find();

        if($data['qrcode']){
            $this->view->assign('data',$data);
        }else{
            header('Content-Type: image/png');
            vendor("phpqrcode.phpqrcode");//引入工具包
            $qRcode = new \QRcode();                
            $path = "public/Uploads/QRcode/";//生成的二维码所在目录

            $time = time().'.png';//生成的二维码文件名 
            $fileName = $path.$time;//1.拼装生成的二维码文件路径 
            $data = 'http://daxiang.meyatu.com/index.php/index/index/login?id='.$id;//网址或者是文本内容
            // $data = 'http://daxiang.meyatu.com/index.php/index/index/index';//网址或者是文本内容
            $level = 'L'; //3.纠错级别：L、M、Q、H  
            $size = 10;//4.点的大小：1到10,用于手机端4就可以了 
            ob_end_clean();//清空缓冲区 
            $qRcode::png($data, $fileName, $level, $size);//生成二维码 //文件名转码 


            $file_name = iconv("utf-8","gb2312",$time); 
            $file_path = $_SERVER['DOCUMENT_ROOT'].'/'.$fileName; //获取下载文件的大小 
            $file_size = filesize($file_path); // 
            $file_temp = fopen ( $file_path, "r" ); //返回的文件 
            header("Content-type:application/octet-stream"); //按照字节大小返回 
            header("Accept-Ranges:bytes"); //返回文件大小 
            header("Accept-Length:".$file_size); //这里客户端的弹出对话框 
            header("Content-Disposition:attachment;filename=".$time);
            $im['qrcode']= '/Uploads/QRcode/'.$time;              
            Db::name('bminfo')->where('id',$id)->update($im);
            $data['qrcode']=$im['qrcode'];
            $this->view->assign('data',$data);

        }
        return $this->view->fetch();

    }
    
    //报名(用户id   活动actid   手机号phone  姓名name)
    public function apply(){
        $info=$this->request->post();
        $a['phone']=$info['phone'];//手机号
        $a['username']=$info['name'];//姓名
        $open=Db::name('bminfo')->field('open_id')->where('id',$info['id'])->find();
        $bm=Db::name('bminfo')->where('id',$info['id'])->update($a);
        $ua['order_sn']=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $ua['status']=2;//未支付
        $ua['uid']=$info['id'];
        $ua['actid']=$info['actid'];
        $uorder=Db::name('user_activity')->insertGetId($ua);
        if($bm && $uorder){
            //$this->wxpay($info['id'],$ua['order_sn'],$open['open_id']);
            //$data['status']=1;//生成订单成功
            //return $data;exit;
            $s="http://daxiang.meyatu.com/index.php/index/index/pay?order_sn=".$ua['order_sn'].'&openid='.$open['open_id'];           
            header("Location:".$s."");exit;
        }else{
            $data['status']=2;//生成订单失败
            return $data;exit;
        }



    } 
   public function pay(){
    // echo '1111';exit;
        $openid=$this->request->param('openid');
        $order_sn=$this->request->param('order_sn');
        
        // dump($order_sn);exit;
        $result=$this->wxpay($order_sn,$openid);
        // dump($result);exit;
        $this->view->assign('jsApiParameters',$result);
        $this->view->assign('openid',$openid);
        return $this->view->fetch();
   }
   public function tixian(){
    // echo '1111';exit;
        $id=$this->request->param('id');
        $info=Db::name('bminfo')->field('id,yongjin,open_id')->where('id',$id)->find();
        $this->view->assign('info',$info);
        return $this->view->fetch();
   }
   //提现实现开始
   public function dotixian(){
        $openid=$this->request->post('openid');
        $yongjin=$this->request->post('yongjin');
        $openid = Request::instance()->param('openid');
        $price = Request::instance()->param('price');
        $user_id = Request::instance()->param('user_id');//用户id
        // print_r($price);die;

        $info = Db::name('bminfo')->where('open_id', $openid)->find();
        if (empty($info)) {
            return $this->no(300, '用户openid错误');
        }
        if ($info['yongjin'] > $price) {
            $money = $yongjin * 100;
            $result = $this->comPay($openid, $money);
            if (empty($result['return_msg'])) {
                $b['tstatus']=1;//提现状态（1 已提现）
                $resultinfo = Db::name('bminfo')->where('open_id', $openid)->udpate($b);
                $a = array(
                    'uid' => $info['id'],
                    'record' => $yongjin,
                    'addtime' => time(),
                );
                Db::name('record')->insert($a);
                if ($resultinfo) {
                    return $this->ok(200, 'ok');
                } else {
                    return $this->no(300, '系统错误');
                }
            } else {
                return $this->no(300, '交易失败');
            }
        } else {
            return $this->no(300, '提现余额不足');
        }

   }
   public function comPay($openid, $price)
    {
        //构建原始数据
        $this->params = [
            'mch_appid' => 'wxafe3f25daebca711',//APPid,
            'mchid' => "1521765611",//商户号,
            'nonce_str' => md5(time()), //随机字符串
            'partner_trade_no' => date('YmdHis'), //商户订单号
            'openid' => $openid, //用户openid
            'check_name' => 'NO_CHECK',//校验用户姓名选项 NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
            //'re_user_name'    => '',//收款用户姓名  如果check_name设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => $price,//金额 单位分
            'desc' => '红包提现',//付款描述
            'spbill_create_ip' => $_SERVER['SERVER_ADDR'],//调用接口机器的ip地址
        ];
        //将数据发送到接口地址
        return $this->send();
    }

    public function sign()
    {
        return $this->setSign($this->params);
    }

    public function send($url)
    {
        $url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        // print_r($this->params);die;
        $res = $this->sign();

        // print_r($res);die;
        $xml = ArrToXml($res);
        // print_r($xml);die;
        $returnData = postData($url, $xml);
        // echo 1;
        // print_r($returnData);
        $info = XmlToArr($returnData);
        // echo 1;
        // print_r($info);die;
        return $info;
    }

    public function no($code, $message, $data = '')
    {
        if ($data == '') {
            $result = array(
                "code" => $code,
                "message" => $message,
                "data" => $data
            );
        } else {
            $result = array(
                "code" => $code,
                "message" => $message,
                "data" => $data
            );
        }
        return json($result);
    }

    public function ok($code, $message, $data = '')
    {
        if ($data == '') {
            $result = array(
                "code" => $code,
                "message" => $message,
            );
        } else {
            $result = array(
                "code" => $code,
                "message" => $message,
                "data" => $data
            );
        }

        return json($result);
    }
    //提现结束
   //支付成功调用
   public function successinfo(){
        $openid=session('useropenid');
        if(session('userpid')){
            $i['pid']=session('userpid');
            $user=Db::name('bminfo')->field('pid')->where('open_id',$openid)->find;
            if($user['pid']!=0){
                 Db::name('bminfo')->where('open_id',$openid)->update($i);
            }
           
            $u['uid']=session('userpid');
            $u['actid']=session('actid');
            session('actid',null);
            session('userpid',null);
            Db::name('user_activity_share')->insert($u);
            //0107新增（佣金）

            $n = rand(1, 100);
            if ($n <= 95) {
                $obj2['total_amount'] = rand(1, 2);
            } else {
                $obj2['total_amount'] = rand(2, 3);
            }
            //红包金额入库
            $b['yongjin']=$obj2['total_amount'];
            Db::name('bminfo')->where('id',session('userpid'))->update($b);
        }
        
        // $result=$this->payLuckyMoney($openid);
        // dump($result);exit;
        // $this->redirect('index/index');
        $s="http://daxiang.meyatu.com/index.php/index/index/index";           
        header("Location:".$s."");exit;
        // return $this->view->fetch();
   }
    //微信支付开始
    private $config = array(
        'appid' => "wxafe3f25daebca711",    /*微信开放平台上的应用id*/
        'mch_id' => "1521765611",   /*微信申请成功之后邮件中的商户id*/
        'api_key' => "abc123456def789ghijklmn123456766",    /*在微信商户平台上自己设定的api密钥 32位*/
        'app_key'  =>  "567b6d7ff310bc2bc8793e6401dbdad7"
    );
 
     /**
     * 微信支付
     * $body         商品介绍
     * $orderid      订单id
     * $out_trade_no 订单单号
     * $total_fee    订单金额（19.9固定的）
     */
    public function wxpay($order_sn,$openid){       
        //$mchid = '1521765611';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        //$appid = 'wxafe3f25daebca711';  //微信支付申请对应的公众号的APPID
        //$appKey = '567b6d7ff310bc2bc8793e6401dbdad7';   //微信支付申请对应的公众号的APP Key
        //$apiKey = 'abc123456def789ghijklmn123456766';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥       
        //①、获取用户openid        
        $openId = $openid;      //获取openid        
        //②、统一下单
        $outTradeNo = $order_sn;    //你自己的商品订单号
        $payAmount = '0.01';          //付款金额，单位:元
        $orderName = '支付测试';    //订单标题
        $notifyUrl = 'http://daxiang.meyatu.com/index/index/notify.php';     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        
        $jsApiParameters = $this->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        $jsApiParameters = json_encode($jsApiParameters,true);  

        return $jsApiParameters;
    }
    public function notify(){
        
        file_put_contents('22222.txt', $xmlData);//
        // dump($xmlData);exit;
         $arr = $this->XmlToArr($xmlData);
        // dump($arr);exit;
        if ($arr['return_code'] == 'SUCCESS' && $arr['result_code'] == 'SUCCESS') {
            $wd['order_id']=$arr['out_trade_no'];
            $order['status']=21;//支付状态 1，支付成功
            Db::name('user_activity')->where($wd)->update($order);                   
            
        } else {
            $wd['order_id']=$arr['out_trade_no'];

            $order['status']=3;//支付状态 3，支付失败
            Db::name('user_activity')->where($wd)->update($order);
            file_put_contents('3333.txt', '支付失败!');          
        }
        echo 'success';
    }
   

    

   
    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 统一下单
     * @param string $openid 调用【网页授权获取用户信息】接口获取到用户在该公众号下的Openid
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 支付时间
     * @return string
     */
    public function createJsBizPackage($openid, $totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)
    {
        
        
        // $orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => 'wxafe3f25daebca711',
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $orderName,
            'mch_id' => '1521765611',
            'nonce_str' => $this->createNonceStr(),
            'notify_url' => $notifyUrl,
            'openid' => $openid,            //rade_type=JSAPI，此参数必传
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => $_SERVER['REMOTE_ADDR'],
            'total_fee' => intval($totalFee * 100),       //单位 转为分
            'trade_type' => 'JSAPI',
        );
        
        $unified['sign'] = $this->getSign($unified, 'abc123456def789ghijklmn123456766');
        
        $responseXml = $this->curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', $this->arrayToXml($unified));
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
       
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }       
        $arr = array(
            "appId" => 'wxafe3f25daebca711',
            "timeStamp" => "$timestamp",        //这里是字符串的时间戳，不是int，所以需加引号
            "nonceStr" => $this->createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
        );
       
        $arr['paySign'] = $this->getSign($arr, 'abc123456def789ghijklmn123456766');
        
        return $arr;
    }

    public function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function curlPost($url = '', $postData = '', $options = array())
    {
        
        // if (is_array($postData)) {
        //     $postData = http_build_query($postData);
        // }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function createNonceStr($length = 16)
    {
        
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = $this->formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    //微信支付结束
   
    /**
     * 发放现金红包
     * $str   支付的人的上级id
     */
    public function payLuckyMoney($str)
    {
        //获得上级的信息
        $where['open_id']=$str;
        $in=Db::name('bminfo')->field('pid')->where($where)->find();
        $info=Db::name('bminfo')->field('client_id,open_id')->where('id',$in['pid'])->find();
        $obj2 = array();
        //appid
        $obj2['wxappid'] = 'wxafe3f25daebca711';
        //商户id
        $obj2['mch_id'] = '1521765611';
        //组合成28位，根据官方开发文档，可以自行设置
        $obj2['mch_billno'] = "abc123456def789ghijklmn123456766" . date('YmdHis') . rand(1000, 9999);
        // 调用接口的机器IP地址
        $obj2['client_ip'] = $info['client_id'];
        //接收红包openid
        $obj2['re_openid'] = $info['open_id'];

        /* 付款金额设置start，按照概率设置随机发放。
         * 1-200元之间，单位分。这里设置95%概率为1-2元，5%的概率为2-10元
         */
        $n = rand(1, 100);
        if ($n <= 95) {
            $obj2['total_amount'] = rand(1, 2);
        } else {
            $obj2['total_amount'] = rand(2, 3);
        }
        
        /* 付款金额设置end */

        // 红包个数
        $obj2['total_num'] = 1;
        // 商户名称
        $obj2['send_name'] = "大象教育";
        // 红包祝福语
        $obj2['wishing'] = "恭喜发财，大吉大利";
        // 活动名称
        $obj2['act_name'] = "大象教育分享领红包";
        // 备注
        $obj2['remark'] = "大象教育红包";

        /* 文档中未说明以下变量，李富林博客中有。注释起来也没问题。不需要。
        $obj2['min_value'] = $money;
        $obj2['max_value'] = $money;
        $obj2['nick_name'] = '小门太红包';
        */
        
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        $isPay = pay_lucky_money($url, $obj2);//调用commom.php里面的方法
        dump($isPay);exit;
        $res = $this->XmlToArr($isPay);
        dump($res);exit;
        // 发放成功，把红包数据插入数据库
        if ($res['return_msg'] == '发放成功') {
            //发放成功，进行逻辑处理
            //红包金额入库
            $b['yongjin']=$obj2['total_amount'];
            Db::name('bminfo')->where('id',$info['id'])->update($b);
            return $res['return_msg'];
            //红包金额入库结束
        } else {
            // 发放失败，返回失败原因
            return $res['return_msg'];
        }
    } 
    public function XmlToArr($xml)
    {
        if($xml == '') return '';
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }
    // //用于请求微信接口获取数据
    // function get_by_curl($url,$post = false){
    //     $ch = curl_init();
    //     curl_setopt($ch,CURLOPT_URL,$url);
    //     curl_setopt($ch,CURLOPT_HEADER, 0);
    //     curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    //     if($post){
    //         curl_setopt($ch,CURLOPT_POST, 1);
    //         curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    //     }
    //     $result = curl_exec($ch);
    //     curl_close($ch);
    //     return $result;
    // }

}