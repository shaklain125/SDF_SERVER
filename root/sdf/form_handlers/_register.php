<?php
include '../_importPhp.php';

if(isset($_POST['register']))
{
  // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
  $regType = $_POST['regType'];
  if(ArrayNotNull($_POST))
  {
    $usrname = $_POST['input_username'];
    $email = $_POST['input_email'];
    $password = $_POST['input_pw'];
    $firstname = $_POST['input_fname'];
    $lastname = $_POST['input_lname'];
    if(isset($_POST['terms']))
    {
      if($_POST['terms'] == "on")
      {
        registerUser($usrname,$email,$password,$firstname,$lastname, $regType);
      }else {
        errorMsg('Please accept terms and policies');
      }
    }else {
      errorMsg('Please accept terms and policies');
    }
  }else {
    errorMsg('Please fill all input boxes');
  }
}else {
  header('Location: ../index.php');
}
function errorMsg($msg)
{
  echo json_encode(array(
    'errorMsg' => $msg
  ));
}

function registerUser($usrname,$email,$password,$firstname,$lastname,$regType)
{
  $conn = createSqlConn();
  if($conn != null)
  {
    if(!checkUserExists($conn,$usrname,$email))
    {
      $q = "INSERT INTO registereduser (username, firstname, lastname, email, password) VALUES ('$usrname','$firstname','$lastname','$email','$password')";
      if(getQueryResult($q,$conn))
      {
        session_unset();
        startSession();
        $last_id = $conn->insert_id;
        if($regType == 1)
        {
          $dob = $_POST['input_dob'];
          $university = $_POST['input_university'];
          $graduation = $_POST['input_graduation'];
          $q1 = "INSERT INTO student (userid,dob,graduation_date,university) VALUES ($last_id,'$dob','$graduation','$university')";
          if(getQueryResult($q1,$conn))
          {
            // echo 'Student account created';
            $reguser = new student($last_id);
            $_SESSION['student'] = serialize($reguser);
            echo json_encode(array(
              'message' => 'ok'
            ));
          }else {
            // echo "Error: " . $q1 . "<br>" . $conn->error;
          }
        }elseif ($regType == 2) {
          $q1 = "INSERT INTO storemember (userid) VALUES ($last_id)";
          if(getQueryResult($q1, $conn))
          {
            // echo 'Store member account created.';
            $reguser = new storemember($last_id);
            $_SESSION['storemember'] = serialize($reguser);
            echo json_encode(array(
              'message' => 'ok'
            ));
          }
        }
      }
    }else {
      errorMsg("username or email already exists");
    }
  }
  closeSqlConn($conn);
}

function checkUserExists($conn,$usrname,$email)
{
  $result = getQueryResult("select username,email from registereduser",$conn);
  if ($result->num_rows > 0)
  {
    while($row = $result->fetch_assoc()) {
      if(($row['username'] == $usrname) || $row['email'] == $email)
      {
        return true;
      }
    }
    return false;
  }else {
    return false;
  }
}
?>
