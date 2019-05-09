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
    


    public function index()
    {
            
            if ($this->request->param('code')==''){//没有code，去微信接口获取code码
                $this->getcodess();
            } else {//获取code后跳转回来到这里了
                $code=$this->request->param('code');
                $userinfo = $this->getUserInfo($code);
                dump($userinfo);exit;

                
            }        

            
        
            //得到活动
            $act=Db::name('activity')->where('status',1)->find();
            if($act){
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
                if($this->request->param('id')){
                    $info['pid']=$this->request->param('id');
                    $us['uid']=$this->request->param('id');
                    $us['actid']=$act['id'];
                    $sh=Db::name('user_activity_share')->where($us)->find();
                    if($sh){
                        Db::name('user_activity_share')->insert($us);  
                    }
                    
                } 
                $action=explode('，',$act['action']);  
                
                $this->view->assign('action',$action);
                
                
                // dump($act);exit;
                // dump($act);exit;
                //得到参与人(分享)
                $wu['actid']=$act['id'];
                $share=Db::name('user_activity_share')->field('uid')->where($wu)->select();
                $user=array();
                foreach($share as $kk=>$v){
                    $user[]=Db::name('bminfo')->field('id,name,image')->where('id',$v['uid'])->find();
                }
                $this->view->assign('user',$user);
                //得到购买人
                $wb['actid']=$act['id'];
                $buy=Db::name('user_activity')->field('uid')->where($wb)->select();
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
    public function getUserInfo($code)
    {
        $appid = "wxafe3f25daebca711";
        $appsecret = "567b6d7ff310bc2bc8793e6401dbdad7";

        //Get access_token
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $access_token_json = $this->https_request($access_token_url);//自定义函数
        $access_token_array = json_decode($access_token_json,true);//对 JSON 格式的字符串进行解码，转换为 PHP 变量，自带函数
        
        //获取access_token
        $access_token = $this->get_Accesstoken();//获取access_token对应的值

        //获取openid
        $openid = $access_token_array['openid'];//获取openid对应的值
        $userinfo_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        // $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
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
        // file_put_contents("log3233.log",22222);
        $s="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxafe3f25daebca711&redirect_uri=http://daxiang.meyatu.com/index/index/index&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
        header("Location:".$s."");exit;
    }
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
   

}