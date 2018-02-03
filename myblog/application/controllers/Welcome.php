<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Article_model');
        $this->load->model('User_model');
    }

    public function index()
	{
        $this->load->library('pagination');

        $total = $this->Article_model->get_article_count();

        $config['base_url'] = base_url().'welcome/index';
        $config['total_rows'] = $total;
        $config['per_page'] = 1;
        $config['first_link'] = false;
        $config['last_link'] = false;
        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();
        $results = $this->Article_model->get_article_list($this->uri->segment(3),$config['per_page']);

	    $types = $this->Article_model->get_article_type();

		$this->load->view('index',array('list'=>$results,'types'=>$types,'links'=>$links));
	}

    public function vue_login(){

        header('Access-Control-Allow-Origin:* ');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        $name = $this->input->get('username');
        $pwd = $this->input->get('pwd');

        $user = $this->User_model->get_user_by_email_and_pwd($name,$pwd);

//        $this->session->set_userdata(array(
//            "user" => $user
//        ));

        echo json_encode($user);

    }

    public function vue_index(){
        header('Access-Control-Allow-Origin:* ');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');

//        $user = $this->session->userdata('user');

//        echo json_encode($user);
    }

	public function logined()
    {
        $this->load->library('pagination');
        $user = $this->session->userdata('user');


        $total = $this->Article_model->get_article_count_by_user_id($user->user_id);

        $config['base_url'] = base_url().'welcome/logined';
        $config['total_rows'] = $total;
        $config['per_page'] = 1;
        $config['first_link'] = false;
        $config['last_link'] = false;
        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();
//        echo $this->uri->segment(2);
//        die();
        $results = $this->Article_model->get_article_list_by_user_id($user->user_id, $this->uri->segment(3),$config['per_page']);


//	    $user = $this->session->userdata('user');

        $types = $this->Article_model->get_article_type_by_user_id($user->user_id);
        $msg_count = $this->User_model->get_msg_count($user->user_id);

        $this->load->view('index_logined',array(
            'list'=>$results,
            'types'=>$types,
            'links'=>$links,
            'msg_count'=>$msg_count
            ));
    }
    public function adminln()
    {
        $this->load->view('adminIndex');
    }
    public function new_blog()
    {
        $user = $this->session->userdata('user');
        $types = $this->Article_model->get_typename_by_user_id($user->user_id);

        $this->load->view('newBlog',array("types"=>$types));
    }

    public function publish_blog()
    {
        $user = $this->session->userdata('user');
        $title = $this->input->post('title');
        $article_type = $this->input->post('catalog');
        $content = $this->input->post('content');

        date_default_timezone_set('Asia/ShangHai');

        $rows = $this->User_model->publish_blog(array(
            'title' => $title,
            'type_id' => $article_type,
            'content' => $content,
            'user_id' => $user->user_id,
            'post_date' => date("Y-m-d h:m:s")
        ));
        if($rows>0){
            redirect('welcome/logined');
        }

    }

    public function blogs()
    {
        $this->load->library('pagination');
        $user = $this->session->userdata('user');


        $total = $this->Article_model->get_article_count_by_user_id($user->user_id);

        $config['base_url'] = base_url().'welcome/logined';
        $config['total_rows'] = $total;
        $config['per_page'] = 40;
        $config['first_link'] = false;
        $config['last_link'] = false;
        $this->pagination->initialize($config);

        $links = $this->pagination->create_links();
//        echo $this->uri->segment(2);
//        die();
        $results = $this->Article_model->get_article_list_by_user_id($user->user_id, $this->uri->segment(3),$config['per_page']);


//	    $user = $this->session->userdata('user');

        $types = $this->Article_model->get_article_type_by_user_id($user->user_id);
        $this->load->view('blogs',array('list'=>$results,'types'=>$types,'links'=>$links));
    }

    public function chpwd()
    {
        $this->load->view('chpwd');
    }
    public function blogCatalogs()
    {
        $user = $this->session->userdata('user');
        $types = $this->Article_model->get_article_type_by_user_id($user->user_id);

        $this->load->view('blogCatalogs',array("types"=>$types));
    }
    public function add_type()
    {
        $name = $this->input->get('name');
        $user = $this->session->userdata('user');
        $rows = $this->User_model->add_type($name,$user->user_id);
        if($rows > 0){
            echo 'success';
        }
    }
    public function edit_type()
    {
        $name = $this->input->get('name');
        $type_id = $this->input->get('typeId');
        $rows = $this->User_model->edit_type($name,$type_id);
        if($rows > 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    public function delete_type()
    {
        $type_id = $this->input->get('typeId');
        $user = $this->session->userdata('user');
        $result = $this->User_model->get_type_by_id_userid($user->user_id,$type_id);
        if(count($result) == 0){
            echo 'fail';
        }else{
            $rows = $this->User_model->delete_type($type_id);
            if($rows >0){
                echo 'success';
            }
        }
    }
    public function delete_article()
    {
        $ids = $this->input->get('ids');
        $rows = $this->User_model->delete_article_by_id($ids);
        if($rows > 0){
            echo 'success';
        }
    }
    public function blogComments()
    {
        $user = $this->session->userdata('user');
        $result = $this->User_model->blog_comments($user->user_id);

        $this->load->view('blogComments',array(
            'result'=>$result
        ));
    }
    public function blog_detail()
    {
        $id = $this->input->get('id');
        $row = $this->User_model->get_article_by_id($id);
        $date_str = $this->time_tran($row->post_date);
        $row->post_date = $date_str;
        $comments = $this->User_model->get_comment_by_article_id($id);

        //查询上一篇和下一篇
        $result = $this->User_model->get_article_list_all();
        $next_article = null;
        $prev_article = null;
        foreach ($result as $index=>$article){
            if($article->article_id == $id){
                if($index > 0){
                    $prev_article = $result[$index-1];
                }
                if($index < count($result) - 1){
                    $next_article = $result[$index+1];
                }
            }
        }



        $this->load->view('viewPost_logined',array(
            'article'=> $row,
            'comments'=>$comments,
            'next'=>$next_article,
            'prev'=>$prev_article
        ));
    }
    public function inbox()
    {
        $user = $this->session->userdata('user');
        $this->User_model->is_read($user->user_id);
        $messages = $this->User_model->get_message_by_receiver($user->user_id);
        $this->load->view('inbox',array(
            'messages'=>$messages
        ));
    }

    public function outbox()
    {
        $user = $this->session->userdata('user');
        $this->User_model->is_read($user->user_id);
        $messages = $this->User_model->get_message_by_sender($user->user_id);
        $this->load->view('outbox',array(
            'messages'=>$messages
        ));
    }
    public function send_msg()
    {
        $id = $this->input->get('id');
        $user = $this->User_model->get_user_id($id);
        $this->load->view('sendMsg',array(
            'autor'=>$user
        ));

    }

    public function add_comment()
    {
        $content = $this->input->get('content');
        $article_id = $this->input->get('articleId');
        $user = $this->session->userdata('user');
        $rows = $this->User_model->add_comment(array(
           'content'=>$content,
            'user_id'=>$user->user_id,
            'post_date'=>date("Y-m-d h:m:s"),
            'article_id'=>$article_id
        ));
        if($rows >0){
            echo 'success';
        }
    }
    public function send_msg_ok()
    {
        $id = $this->input->post('autorId');
        $content = $this->input->post('content');
        $user = $this->session->userdata('user');
        $rows = $this->User_model->add_msg(array(
            'content'=>$content,
            'sender'=>$user->user_id,
            'post_date'=>date("Y-m-d h:m:s"),
            'receiver'=>$id
        ));

        if($rows >0){
            redirect("welcome/logined");
        }
    }



    function time_tran($the_time)
    {
        $now_time = date("Y-m-d H:i:s", time() + 8 * 60 * 60);
        $now_time = strtotime($now_time);
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        } else {
            if ($dur < 60) {
                return $dur . '秒前';
            } else {
                if ($dur < 3600) {
                    return floor($dur / 60) . '分钟前';
                } else {
                    if ($dur < 86400) {
                        return floor($dur / 3600) . '小时前';
                    } else {
                        if ($dur < 259200) {//3天内
                            return floor($dur / 86400) . '天前';
                        } else {
                            return $the_time;
                        }
                    }
                }
            }
        }
    }

}
