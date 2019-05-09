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

class Index extends Controller
{
    

    public function wuliu()
    {
       
        return $this->view->fetch();

    }
    public function wuliudesc()
    {
        
       $post=$this->request->post();
       $data['']
       dump($post);exit;
    return $this->view->fetch();

    }
    //优惠券
    public function huaijian(){
        

    }
   
 
    
}