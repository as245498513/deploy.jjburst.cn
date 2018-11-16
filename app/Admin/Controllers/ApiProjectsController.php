<?php

namespace App\Admin\Controllers;

use App\Models\ApiProjects;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\MessageBag;

class ApiProjectsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('项目列表')
            ->description('所有项目')
            ->body($this->grid());
    }


    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑项目')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('新增项目')
            ->description('添加')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ApiProjects);



        $grid->id('项目ID');
        $grid->project_name('项目名称');
        $grid->project_desc('项目描述');
        $grid->status('项目状态')->display(function($status){
            $statusStr = '';
            switch ($status) {
                case 0:
                    $statusStr = '未开始';
                    break;
                case 1:
                    $statusStr = '开发中';
                    break;
                case 2:
                    $statusStr = '测试中';
                    break;
                case 3:
                    $statusStr = '已上线';
                    break;
            }
            return $statusStr;
        });
        $grid->test_domain('测试域名');
        $grid->product_domain('正式域名');
        $grid->created_at('创建时间');

        //不显示的按钮
        $grid->disableExport();
        $grid->disableRowSelector();

        //定义筛选项
        $grid->filter(function($filter){
            //禁用ID过滤器
            $filter->disableIdFilter();
            $filter->like('project_name','项目名称');
            /*$filter->column(1/2,function($filter){
                //添加自定义过滤器
                //$filter->like('project_name','项目名称');
                //$filter->equal('status','项目状态')->select([0 => '未开始', 1 => '开发中', 2 => '测试中', 3 => '已上线']);  
            });*/
        });
       
        //每行不显示的按钮
        $grid->actions(function($actions){
            $actions->disableView();
        });
        

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ApiProjects::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ApiProjects);

        //编辑时回显id
        $form->display('id','项目ID');

        $form->text('project_name','项目名称')->rules('required');
        //$form->text('project_desc','项目描述');
       

        $form->dateRange('dev_start_time','dev_end_time', '项目周期'); 

        $statusArr = [
            0 =>'未开始',
            1 =>'开发中',
            2 =>'测试中',
            3 =>'已上线',
        ];

        $form->select('status','项目状态')->options($statusArr);

        $form->text('test_domain','测试域名')->rules('required');
        $form->text('product_domain','正式域名')->rules('required');

        $form->textarea('project_desc', '项目描述');

        if($form->model()->id){
            $form->hidden('last_update_admin_id')->default(Admin::user()->id);
        }else{
            $form->hidden('add_admin_id')->default(Admin::user()->id);
            $form->hidden('last_update_admin_id')->default(Admin::user()->id);
        }

        $form->display('created_at', '创建时间');
        $form->display('updated_at', '修改时间');

        //禁用顶部按钮
        $form->tools(function($tools){
            $tools->disableView();
            $tools->disableDelete();
        });
        //禁用底部按钮
        $form->footer(function($footer){
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
        });


        return $form;
    }
}
