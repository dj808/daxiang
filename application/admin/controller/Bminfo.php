<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author    yuan1994 <tianpian0805@gmail.com>
 * @link      http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 报名人管理
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path'), EXT);

use app\admin\Controller;
use think\Loader;
use think\Request;
use think\Session;
use think\Db;
class Bminfo extends Controller
{

    use \app\admin\traits\controller\Controller;

    protected static $blacklist = [];

    protected function filter(&$map)
    {
        if ($this->request->param('name')) {
            $map['name'] = ["like", "%" . $this->request->param('name') . "%"];
        }
    }

   

    /**
     * 删除限制
     */
    // protected function beforeDelete()
    // {
    //     //禁止删除Admin模块,权限设置节点
    //     $this->filterId([1, 2], '该内容不能被删除');
    // }

  
    public  function index(){
        $listRows = 10;
            
        $list = Db::name('bminfo')->order('id desc')->paginate($listRows, false, ['query' => $this->request->get()]);

        $listnum = Db::name('bminfo')->field('id')->select();
        
        $count = count($listnum);
        $this->view->assign('list', $list);
        $this->view->assign("count", $count);
        // $this->view->assign("page", 5);
        $this->view->assign("page", $list->render());
        // $this->view->assign("count", $list->total());
        return $this->view->fetch();
    }
       /**
     * 添加
     * @return mixed
     */
    public function add()
    {
        $controller = $this->request->controller();
        $module = $this->request->module();
        if ($this->request->isPost()) {

            // 写入数据
            Db::startTrans();
            try {
                header('Content-Type: image/png');
                vendor("phpqrcode.phpqrcode");//引入工具包
                $qRcode = new \QRcode();
                $new = $this->request->post();
                $new['image']='/punlic'.$new['image'];
                $new['create_time']=date('Y-m-d H:i:s',time());
                $info = Db::name('bminfo')->insertGetId($new);
                $n['actid']=$new['actid'];
                unset($new['actid']);
                $ua['uid']=$info;
                $ua['actid']=$n['actid'];
                $uainfo=Db::name('user_activity')->where($ua)->find();
                if($uainfo){
                    return ajax_return_adv_error('已参与过此项目');

                }else{
                    Db::name('user_activity')->insert($ua);
                }
                if($new['status']==1){
                    $sa['uid']=$info;
                    $sa['actid']=$n['actid'];
                    Db::name('user_activity_share')->insert($sa);

                }
                
                $path = "./Uploads/QRcode/";//生成的二维码所在目录
                if(!file_exists($path)){ 
                    mkdir($path, 0700,true); 
                } 
                $time = time().'.png';//生成的二维码文件名 
                $fileName = $path.$time;//1.拼装生成的二维码文件路径 
                $data = 'http://www.baidu.com&id='.$info;//网址或者是文本内容
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
               
                Db::name('bminfo')->where('id',$info)->update($im);
                

                // 提交事务
                Db::commit();
                
                return ajax_return_adv('添加成功');
                
                          
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }

        } else {
            $activity=Db::name('activity')->field('id,title')->select();
            $this->view->assign('activity',$activity) ;         
            return $this->view->fetch(isset($this->template) ? $this->template : 'add');

        }

    }
    /**
     * 修改
     * @return mixed
     */
    public function edit()
    {
        
        $controller = $this->request->controller();
        if ($this->request->isAjax()) {
            $data=$this->request->param('id');
            $goods_data = $this->request->post();

            

            $goods_data['create_time']=date('Y-m-d H:i:s',time());

            unset($goods_data['id']);
            // $sell['goods_destination'] = input('post.goods_destination');
            $goods = Db::name('bminfo')->where('id', $data)->update($goods_data);
            // echo $goods;die;
            if ($goods) {
                return ajax_return_adv('修改成功');
            } else {
                return ajax_return_adv_error('修改失败');
            }
        } else {
            //获取到该数据的值
            $id = $this->request->param('id');
            if (!$id) {
                throw new Exception("缺少参数ID");
            }
            $vo =  Db::name('bminfo')->where('id', $id)->find();
            // print_r($vo);die;
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }
            $uz =  Db::name('user_activity')->where('uid', $id)->find();
           
            //var_dump($new_list);exit;
            $this->view->assign('new_list', $vo);
            $activity=Db::name('activity')->field('id,title')->where('id',$uz['actid'])->find();
            $this->view->assign('activity',$activity) ;   
            return $this->view->fetch();
        }
    }
      /**
     * 删除
     * @return mixed
     */
    public function delete()
    {
        
        $id = $this->request->param('id');
        
        $menus = Db::name('bminfo')->where('id', $id)->delete();
        if ($menus) {
            return ajax_return_adv('删除成功');
        } else {
            return ajax_return_adv_error('删除失败');
        }
    }
}
