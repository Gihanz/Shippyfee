<?php
namespace Phppot;

class UserDetail
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }  

    public function getUserDetail($username)
    {
        $query = 'SELECT * FROM tbl_member where username = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $UserRecord = $this->ds->select($query, $paramType, $paramValue);
		
        return $UserRecord;
    }
	
	public function setAsPaidUser($username, $max_search_count)
    {
        $query = 'UPDATE tbl_member SET is_paid = 1, search_count = 0, max_search_count = ? where username = ?';
        $paramType = 'ss';
        $paramValue = array(
			$max_search_count,
			$username
        );
		$this->ds->insert($query, $paramType, $paramValue);
    }
  
}
