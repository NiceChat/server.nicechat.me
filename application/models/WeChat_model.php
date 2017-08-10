<?php
class WeChat_model extends CI_Model 
{
  // 构造函数
  public function __construct() 
  {
    $this->load->database();
  }

  // 获取 accesstoken
  public function query_token() 
  {
    $query = $this->db->query('SELECT * FROM token LIMIT 1');
    $row = $query->row();
    return $row;
  }

  // 更新数据
  public function update_token($row)
  {
    $sql = 'UPDATE token SET access_token="'.$row["access_token"].'", expires_in='.$row["expires_in"].' WHERE flag="1"';
    $this->db->query($sql);
    return $this->db->affected_rows();
  }

  // 获取 jsapi_ticket
  public function query_ticket()
  {
    $query = $this->db->query('SELECT * FROM ticket LIMIT 1');
    $row = $query->row_array();
    return $row;
  }

  // 更新jsticket
  public function update_ticket($row)
  {
    $sql = 'UPDATE ticket SET jsapi_ticket="'.$row["ticket"].'", expires_in='.$row["expires_in"].' WHERE flag="ticket"';
    $this->db->query($sql);
    return $this->db->affected_rows();
  }
}