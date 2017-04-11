<?php
  /**
  * the model of the blog
  */
  class Blogs_Model extends CI_Model
  {
    
    function __construct()
    {
      $this->load->database();
    }

    // 获取博客列表ORDER BY mtime
    public function get_blogs()
    {
      $sql = 'select * from blogs order by mtime DESC';
      $query = $this->db->get_where('blogs');
      return $query->result_array();
    }

    // 获取单条信息
    public function get_one_blog($id)
    { 
      try {
        $sql = 'select * from blogs WHERE id='.$id;
        $query = $this->db->query($sql);
        // $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row();
      } catch (Exception $e) {
        $error = $this->db->error();
      }
    }

    // 插入一条博客
    public function insert_one_blog($title, $content, $ctime, $author)
    {
      $sql = "INSERT INTO blogs (title, content, ctime, mtime, author) VALUES (".$this->db->escape($title).", ".$this->db->escape($content).",".$this->db->escape($ctime).",".$ctime.",".$this->db->escape($author).")";
      $this->db->query($sql);
      // 返回影响的行数
      return $this->db->affected_rows();
    }

    // 更新一条博客
    public function update_one_blog($id, $title, $content, $author)
    {
      $mtime = time();
      $sql = "UPDATE blogs SET  title = ".$this->db->escape($title).", content = ".$this->db->escape($content).", mtime = ".$mtime.", author = ".$this->db->escape($author)." WHERE id = ".$id;
      $this->db->query($sql);
      // 返回影响条目数
      return $this->db->affected_rows();
    }

    public function delete_one_blog($id)
    {
      $sql = 'DELETE FROM blogs WHERE id = '.$id;
      $this->db->query($sql);
      // 返回删除的条目数
      return $this->db->affected_rows();
    }
  }