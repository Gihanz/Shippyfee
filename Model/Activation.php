<?php
namespace Phppot;

class Activation
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }  

    public function validateActivationCode($code)
    {
        $query = 'SELECT * FROM tbl_member where activation_code = ?';
		$paramType = 's';
		$paramValue = array(
			$code
		);
		$countRecord = $this->ds->getRecordCount($query, $paramType, $paramValue);
				
        return $countRecord;
    }
	
	public function checkAllreadyActivated($code)
    {
        $query = 'SELECT * FROM tbl_member where is_active = 0 AND activation_code = ?';
		$paramType = 's';
		$paramValue = array(
			$code
		);
		$countRecord = $this->ds->getRecordCount($query, $paramType, $paramValue);
				
        return $countRecord;
    }
	
	public function activate($code)
    {
        $query = 'UPDATE tbl_member SET is_active = 1 where activation_code = ?';
        $paramType = 's';
        $paramValue = array(
			$code
        );
		$this->ds->insert($query, $paramType, $paramValue);
    }
  
}
