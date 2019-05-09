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
// 优惠券管理
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path'), EXT);

use app\admin\Controller;
use think\Loader;
use think\Request;
use think\Session;
use think\Db;
class Coupon extends Controller
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
    protected function beforeDelete()
    {
        //禁止删除Admin模块,权限设置节点
        $this->filterId([1, 2], '该内容不能被删除');
    }

  
    public  function index(){
        $listRows = 10;
            
        $list = Db::name('coupon')->order('id desc')->paginate($listRows, false, ['query' => $this->request->get()]);
        $listnum = Db::name('coupon')->field('id')->select();
        
        $count = count($listnum);
       
        $this->view->assign("count", $count);
        // $this->view->assign("page", 5);
        $this->view->assign("page", $list->render());
        // $this->view->assign("count", $list->total());
        $list=$list->all();
        foreach($list as $k=>$v){
            $u=Db::name('bminfo')->field('name')->where('id',$v['uid'])->find();
            $list[$k]['uname']=$u['name'];
        }
         $this->view->assign('list', $list);
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
                $new = $this->request->post();
                $new['create_time']=date('Y-m-d H:i:s',time());
                $info = Db::name('coupon')->insert($new);
                            
                // 提交事务
                Db::commit();
                
                return ajax_return_adv('添加成功');
                
                          
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }

        } else {
            $user=Db::name('bminfo')->select();
            $this->view->assign('user',$user);
                        
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
            $goods = Db::name('coupon')->where('id', $data)->update($goods_data);
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
            $vo =  Db::name('coupon')->where('id', $id)->find();
            // print_r($vo);die;
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }
           
           // var_dump($new_list);exit;
            $this->view->assign('new_list', $vo);
            $user=Db::name('bminfo')->select();
            $this->view->assign('user',$user);
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
        $menus = Db::name('coupon')->where('id', $id)->delete();
        if ($menus) {
            return ajax_return_adv('删除成功');
        } else {
            return ajax_return_adv_error('删除失败');
        }
    }
}
