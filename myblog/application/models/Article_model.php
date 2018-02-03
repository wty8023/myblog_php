<?php

/**
 * Created by PhpStorm.
 * User: 王天禹
 * Date: 2018/1/13
 * Time: 15:20
 */
class Article_model extends CI_Model
{
    public function get_article_list($offset,$page_size)
    {
//        echo $offset;
//        echo $page_size;
//        die();
//        $sql = "select * from t_article a, t_article_type t where a.type_id = t.type_id limit $offset,$page_size";
        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $this->db->limit($page_size, $offset);
        $query = $this->db->get();
//        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_article_list_by_user_id($user_id,$offset,$page_size)
    {
        if($offset == null ){
            $offset = 0;
        }
//        echo $offset;
//        die();
        /*$this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $this->db->limit($page_size, $offset);
        $query = $this->db->get();*/
        $this->db->select('*');
        $this->db->from('t_article a');
        $this->db->join('t_article_type t', 'a.type_id = t.type_id','left');
        $this->db->where('a.user_id',$user_id);
        $this->db->order_by('a.article_id','desc');
        $this->db->limit($page_size, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_article_count()
    {
        return $this->db->count_all('t_article');
    }
    public function get_article_count_by_user_id($user_id)
    {
        $query = $this->db->get_where('t_article',array('user_id'=>$user_id));
        return count($query->result());
//        return $this->db->count_all('t_article');
    }

    public function get_article_type()
    {
        $sql ="select * from
                 (select count(*) num,a.type_id
                 from t_article a 
                GROUP BY a.type_id) nt,
                t_article_type t 
                where t.type_id = nt.type_id";

        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_article_type_by_user_id($user_id)
    {
//        echo $user_id;
        /*$sql ="select * from
                 (select count(*) num,a.type_id
                 from t_article a where a.user_id = $user_id
                GROUP BY a.type_id) nt,
                t_article_type t 
                where t.type_id = nt.type_id";*/
        $sql = "select * ,(select count(*) from t_article a
                where a.type_id = t.type_id) num from 
                t_article_type t 
                where t.user_id = $user_id";
//        echo $sql;
        $query = $this->db->query($sql);
//        echo $query;
//        die();
//        var_dump($query);
//        die();
        return $query->result();
    }
    public function get_typename_by_user_id($user_id)
    {
        $sql = "select * from t_article_type where t_article_type.user_id = $user_id ";
        $query = $this->db->query($sql);
        return $query->result();
    }
}