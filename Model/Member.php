<?php
namespace Phppot;

ini_set("include_path", '/home2/shippyfe/php:' . ini_get("include_path") );
use Mail;
use Mail_Mime;
require_once "Mail.php";
require_once "Mail/mime.php";

class Member
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * to check if the username already exists
     *
     * @param string $username
     * @return boolean
     */
    public function isUsernameExists($username)
    {
        $query = 'SELECT * FROM tbl_member where username = ?';
        $paramType = 's';
        $paramValue = array(
            $username
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
    public function registerMember()
    {
        $isUsernameExists = $this->isUsernameExists($_POST["username"]);
        $isEmailExists = $this->isEmailExists($_POST["email"]);
        if ($isUsernameExists) {
            $response = array(
                "status" => "error",
                "message" => "Username already exists."
            );
        } else if ($isEmailExists) {
            $response = array(
                "status" => "error",
                "message" => "Email already exists."
            );
        } else {
            if (! empty($_POST["signup-password"])) {

                // PHP's password_hash is the best choice to use to store passwords
                // do not attempt to do your own encryption, it is not safe
                $hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);
				$activationcode = md5($_POST["email"].time());
            }
            $query = 'INSERT INTO tbl_member (username, password, email, full_name, phone_number, activation_code) VALUES (?, ?, ?, ?, ?, ?)';
            $paramType = 'ssssss';
            $paramValue = array(
                $_POST["username"],
                $hashedPassword,
                $_POST["email"],
				$_POST["fullname"],
				$_POST["phone"],
				$activationcode
            );
            $memberId = $this->ds->insert($query, $paramType, $paramValue);
            if (! empty($memberId)) {
                $response = array(
                    "status" => "success",
                    "message" => "You have registered successfully. Please verify in the registered Email."
                );
				
                $from = "ShippyFee <shippyfee@shippyfee.com>";
                $to = $_POST["email"];
                $subject = "Email Verification (shippyfee.com)";
                
                $headers = array ('From' => $from,
                                  'To' => $to,
                                  'Subject' => $subject);
                
                $fullname = $_POST["fullname"];
    			$base_url = "https://" . $_SERVER['SERVER_NAME'];
    			$html = "<html></body><div><div>Dear $fullname,</div></br></br>";
    			$html .= "<div style='padding-top:8px;'>Please click on the link below to verify your e-mail and <b> Activate ShippyFee </b> account.</div>
    				      <div style='padding-top:10px; font-weight: 800;'><a href='$base_url/activate-member.php?code=$activationcode' style='color:red;'>>>> Activate ShippyFee <<<</a></div>
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
                
                session_start();
				$_SESSION["email"] = $to;
				session_write_close();
				$url = "./user-registration-notify.php";
				header("Location: $url");
            }
        }
        return $response;
    }

    public function getMember($username)
    {
        $query = 'SELECT * FROM tbl_member where username = ? AND is_active = 1';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $memberRecord = $this->ds->select($query, $paramType, $paramValue);
        return $memberRecord;
    }

    /**
     * to login a user
     *
     * @return string
     */
    public function loginMember()
    {
        $memberRecord = $this->getMember($_POST["username"]);
        $loginPassword = 0;
        if (! empty($memberRecord)) {
            if (! empty($_POST["login-password"])) {
                $password = $_POST["login-password"];
            }
            $hashedPassword = $memberRecord[0]["password"];
            $loginPassword = 0;
            if (password_verify($password, $hashedPassword)) {
                $loginPassword = 1;
            }
        } else {
            $loginPassword = 0;
        }
        if ($loginPassword == 1) {
            // login success so store the member's username in
            // the session
            session_start();
            $_SESSION["username"] = $memberRecord[0]["username"];
			$_SESSION["fullname"] = $memberRecord[0]["full_name"];
			$_SESSION["email"] = $memberRecord[0]["email"];
            session_write_close();
            $url = "./home.php";
            header("Location: $url");
        } else if ($loginPassword == 0) {
            $loginStatus = "Invalid username or password.";
            return $loginStatus;
        }
    }
}
