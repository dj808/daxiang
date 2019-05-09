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
// 活动管理
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path'), EXT);

use app\admin\Controller;
use think\Loader;
use think\Request;
use think\Session;
use think\Db;
class Activity extends Controller
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
            
        $list = Db::name('activity')->order('id desc')->paginate($listRows, false, ['query' => $this->request->get()]);
        $listnum = Db::name('activity')->field('id')->select();
        
        $count = count($listnum);
       
        $this->view->assign("count", $count);
        // $this->view->assign("page", 5);
        $this->view->assign("page", $list->render());
        $list=$list->all();
        foreach($list as $k=>$v){
            if($v['end_time'] < date('Y-m-d',time())){
                $list[$k]['status']=2;//已过期
                $s['status']=2;
                Db::name('activity')->where('id',$v['id'])->update($s);
            }
            
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
                $where['status']=1;
                $act=Db::name('activity')->field('id,end_time')->where($where)->find();
                if($act['end_time'] >= date('Y-m-d',time())){
                    return ajax_return_adv_error("还有活动没有结束，请去手动操作");

                }
                $new = $this->request->post();
                $new['create_time']=date('Y-m-d H:i:s',time());
                $info = Db::name('activity')->insert($new);
                $s['status']=2;
                Db::name('activity')->where('id',$act['id'])->update($s);          
                // 提交事务
                Db::commit();
                
                return ajax_return_adv('添加成功');
                
                          
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();

                return ajax_return_adv_error($e->getMessage());
            }

        } else {
            
                        
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
            $goods = Db::name('activity')->where('id', $data)->update($goods_data);
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
            $vo =  Db::name('activity')->where('id', $id)->find();
            // print_r($vo);die;
            if (!$vo) {
                throw new HttpException(404, '该记录不存在');
            }
           
           // var_dump($new_list);exit;
            $this->view->assign('new_list', $vo);
            
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
        $s['status']=2;
        $menus = Db::name('activity')->where('id', $id)->update($s);
        if ($menus) {
            return ajax_return_adv('终止成功');
        } else {
            return ajax_return_adv_error('终止失败');
        }
    }
}
