<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 18/1/11
 * Time: 上午10:40
 */
class User_model extends CI_Model
{

//    public function add($email,$name,$gender,$province,$city,$pwd){
//        $this->db->insert('t_user',array(
//            'email'=>$email,
//            'password'=>$pwd,
//            'username'=>$name,
//            'sex'=>$gender,
//            'province'=>$province,
//            'city'=>$city
//        ));
//        return $this->db->affected_rows();
//    }
    public function add($user){
        $this->db->insert('t_user',$user);
        return $this->db->affected_rows();
    }

    public function test($email){
        $query = $this->db->get_where('t_user', array('email' => $email));
        return $query->result();
    }

    public function user_list(){
        $query = $this -> db -> get("t_user");
//        $query = $this -> db -> get_where("t_user",array('name'=>'lisi'));

        return $query->result();
    }

    public function del_user($id){
        $this->db->delete('t_user', array('id' => $id));
        return $this->db->affected_rows();
    }

    public function get_user_by_id($id){
        $query = $this->db->get_where('t_user', array('id' => $id));
        return $query->row();
    }

    public function update($id,$name){
        $this->db->where('id', $id);
        $this->db->update('t_user', array(
            "name" => $name,
        ));
        return $this->db->affected_rows();
    }
    public function publish_blog($article)
    {
        $this->db->insert('t_article',$article);
        return $this->db->affected_rows();
    }

    public function add_type($name,$user_id)
    {
        $this->db->insert('t_article_type',array(
            'type_name'=>$name,
            'user_id'=>$user_id
        ));
        return $this->db->affected_rows();
    }
    public function edit_type($name,$type_id)
    {
        $this->db->where('type_id', $type_id);
        $this->db->update('t_article_type', array(
            "type_name" => $name
        ));
        return $this->db->affected_rows();
    }
    public function delete_type($type_id)
    {
        $this->db->delete('t_article_type', array('type_id' => $type_id));
        return $this->db->affected_rows();
    }
    public function get_type_by_id_userid($user_id,$type_id){
        $query = $this->db->get_where('t_article_type',array(
            'user_id'=>$user_id,
            'type_id'=>$type_id
        ));
        return $query->result();
    }
    public function delete_article_by_id($ids)
    {
        $this->db->where_in('article_id',$ids);
        $this->db->delete('t_article');
        return $this->db->affected_rows();
    }
    public function get_article_by_id($id){
        $query = $this->db->get_where('t_article',array('article_id'=>$id));
        return $query->row();
    }
    public function get_comment_by_article_id($id){
        $this->db->select('*');
        $this->db->from('t_comment c');
        $this->db->join('t_user u','c.user_id=u.user_id');
        $this->db->where('c.article_id',$id);
        return $this->db->get()->result();
    }
    public function get_article_list_all()
    {
        return $this->db->get('t_article')->result();
    }
    public function add_comment($comment)
    {
        $this->db->insert('t_comment',$comment);
        return $this->db->affected_rows();
    }
    public function blog_comments($user_id)
    {
        $sql ="select * 
            from t_user u,
            t_article a,
            t_comment c 
            where c.user_id = u.user_id 
            and
             c.article_id = a.article_id
            and a.user_id = $user_id";
        return $this->db->query($sql)->result();
    }
    public function get_user_id($user_id)
    {
        $query = $this->db->get_where('t_user', array('user_id' => $user_id));
        return $query->row();
    }
    public function add_msg($msg)
    {
        $this->db->insert('t_message',$msg);
        return $this->db->affected_rows();
    }
    public function get_msg_count($user_id)
    {
        $query = $this->db->get_where('t_message',array('receiver'=>$user_id,'is_read'=>0));
        return count($query->result());
    }
    public function is_read($user_id)
    {
        $sql = "update t_message set is_read = 1 where receiver = $user_id";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }
}