<?php
namespace Phppot;

ini_set("include_path", '/home2/shippyfe/php:' . ini_get("include_path") );
use Mail;
use Mail_Mime;
require_once "Mail.php";
require_once "Mail/mime.php";

class ForgotPassword
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * to check if the email already exists
     *
     * @param string $email
     * @return boolean
     */
    public function isEmailExists($email)
    {
        $query = 'SELECT * FROM tbl_member where email = ?';
        $paramType = 's';
        $paramValue = array(
            $email
        );
        $resultArray = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($resultArray)) {
            $count = count($resultArray);
        }
        if ($count > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * to signup / register a user
     *
     * @return string[] registration status message
     */
    public function sendCredentials()
    {
        $isEmailExists = $this->isEmailExists($_POST["email"]);
        if (!$isEmailExists) {
            $response = array(
                "status" => "error",
                "message" => "Email not exists."
            );
        } else {  
			$email = $_POST["email"];		
            $query = 'SELECT * FROM tbl_member where email = ? AND is_active = 1';
			$paramType = 's';
			$paramValue = array(
				$email
			);
			$memberRecord = $this->ds->select($query, $paramType, $paramValue);
            if (! empty($memberRecord)) {
				
				$accUsername = $memberRecord[0]["username"];
				$newPassword = rand(999, 99999);
				$query = 'UPDATE tbl_member SET password = ? where email = ?';
				$paramType = 'ss';
				$paramValue = array(
					password_hash($newPassword, PASSWORD_DEFAULT),
					$email
				);
				$this->ds->insert($query, $paramType, $paramValue);
		
                $response = array(
                    "status" => "success",
                    "message" => "Your new login credentials sent to E-mail."
                );
				
				$from = "ShippyFee <shippyfee@shippyfee.com>";
                $to = $_POST["email"];
                $subject = "Forgot Password (shippyfee.com)";
                
                $headers = array ('From' => $from,
                                  'To' => $to,
                                  'Subject' => $subject);
                
                $fullname = $_POST["fullname"];
    			$base_url = "https://" . $_SERVER['SERVER_NAME'];				
    			$html = "<html></body><div><div>Dear $fullname,</div></br></br>";
    			$html .= "<div style='padding-top:8px;'>Please use following credentials to login</div>
						  <div style='padding-top:8px;'>Username : <b> $accUsername </b></div>
						  <div style='padding-top:8px;'>New Password : <b> $newPassword </b></div>
    			          <div style='padding-top:40px;'>Powered by <a href='https://shippyfee.com'>shippyfee.com</a></div>
    				      <img src='https://shippyfee.com/assets/img/favicon.png' alt='ShippyFee Logo' width='100px'>				
    				      </div>
    				      </body></html>";
    			$crlf = "\r\n";
    			
    			// Creating the Mime message
                $mime = new Mail_mime($crlf);
                $mime->setHTMLBody($html);
                $body = $mime->get();
                   
                $host = "mail.shippyfee.com";
                $username = "shippyfee@shippyfee.com";
                $password = "rahulacollege@123";
                
                $headers = $mime->headers($headers);
                $smtp = Mail::factory('smtp',
                array ('host' => $host,
                        'auth' => true,
                        'username' => $username,
                        'password' => $password));
                $mail = $smtp->send($to, $headers, $body);

            }else{
				$response = array(
                    "status" => "error",
                    "message" => "Your shippyfee account is not activated. Please verify E-mail to activate."
                );				
			}
        }
        return $response;
    }
}
