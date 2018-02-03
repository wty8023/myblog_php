<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function login()
    {
        $email = $this->input->post('name');
        $this->load->view('login');
    }

    public function captcha(){
        $this->load->helper('captcha');
        $rand = rand(1000,9999);

        $this->session->set_userdata(array(
            'captcha' => $rand
        ));

        $vals = array(
            'word'      => $rand,
            'img_path'  => './captcha/',
            'img_url'   => base_url().'/captcha',
            'font_path' => './path/to/fonts/texb.ttf',
            'img_width' => '100',
            'img_height'    => 30,
            'expiration'    => 300,
            'word_length'   => 50,
            'font_size' => 90,
//            'img_id'    => 'Imageid',
//            'pool'      => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors'    => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );

        $cap = create_captcha($vals);
        $img = $cap['image'];
        return $img;
    }

    public function change_code(){
        $img = $this->captcha();
        echo $img;
    }

    public function reg()
    {
        $cap = $this->captcha();
        $this->load->view('reg',array('img'=>$cap));

    }
    public function add()
    {
        /*$email = $this->input->post('email');
        $name = $this->input->post('name');
        $gender = $this->input->post('gender');
        $province = $this->input->post('province');
        $city = $this->input->post('ciy');
        $pwd = $this->input->post('pwd');
        $pwd2 = $this->input->post('pwd2');
        $row = $this->User_model->add($email,$name,$gender,$province,$city,$pwd);
        if($row>0){
            redirect('user/login');
        }else{
            echo '注册失败';
        }*/
        $email = $this->input->get('email');
        $name = $this->input->get('name');
        $gender = $this->input->get('gender');
        $province = $this->input->get('province');
        $city = $this->input->get('ciy');
        $pwd = $this->input->get('pwd');
        $pwd2 = $this->input->get('pwd2');
        $code = $this->input->get('code');
        if($pwd != $pwd2){
            echo 'pwd-error';
            die();
        }

        $captcha = $this->session->userdata('captcha');

        if($code != $captcha){
            echo 'code-error';
            die();
        }

        $rows = $this->User_model->add(array(
            'username'=>$name,
            'email'=>$email,
            'password'=>$pwd,
            'sex'=>$gender,
            'province'=>$province,
            'city'=>$city
        ));
        if($rows > 0){
//            redirect('url/login');
            echo 'success';
        }else{
            echo 'fail';
        }
    }

    public function check_login()
    {
        $email = $this->input->get('email');
        $pwd = $this->input->get('pwd');
        $result = $this->User_model->test($email);
        if(count($result) == 0){
            echo 'email not exist';
        }else if($result[0]->password == $pwd){
            $this->session->set_userdata(array(
                'user'=>$result[0]
            ));
            echo 'success';
        }else{
            echo 'password error';
        }
    }

    public function test()
    {
        $email = $this->input->get('email');
        $result = $this->User_model->test($email);
        if(count($result )> 0){
            echo 'exist';
        }else{
            echo '0';
        }
    }

    public function auto_login()
    {
        $email = $this->input->get('email');
        $result = $this->User_model->test($email);
        $this->session->set_userdata(array(
            'user'=>$result[0]
        ));
        redirect('welcome/logined');
    }

    public function logout()
    {
        $this->session->unset_userdata('user');
        redirect('welcome/index');
    }
}
