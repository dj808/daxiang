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
// 会员管理
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path'), EXT);

use app\admin\Controller;
use think\Loader;
use think\Request;
use think\Session;
use think\Db;
class User extends Controller
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
            
        $list = Db::name('bminfo')->order('id desc')->paginate($listRows, false, ['query' => $this->request->get()]);
        $listnum = Db::name('bminfo')->field('id')->select();
        

        $count = count($listnum);
       
        $this->view->assign("count", $count);
        // $this->view->assign("page", 5);
        $this->view->assign("page", $list->render());
        $list=$list->all();
        foreach($list as $k=>$v){
            $user=Db::name('bminfo')->field('id,name,phone')->where('id',$v['pid'])->find();
            if($user){
                $list[$k]['pname']=$user['name'];
            }else{
                $list[$k]['pname']='第一级';
            }
            
        }
        $this->view->assign('list', $list);
        // $this->view->assign("count", $list->total());
        return $this->view->fetch();
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
