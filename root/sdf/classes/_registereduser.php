<?php
  // include 'helper.php';
  /**
   *
   */
  class registereduser
  {
    private $reguser;
    function __construct($userid = null)
    {
      if($userid != null)
      {
        $conn = createSqlConn();
        $this->reguser = SqlResultToArray('select * from registereduser where userid='.$userid,$conn);
        closeSqlConn($conn);
      }
    }
    public function SignIn($username, $password)
    {
      $conn = createSqlConn();
      $q = 'SELECT * from registereduser WHERE registereduser.userid IN (SELECT searchUser.userid FROM (SELECT r.userid, STRCMP(BINARY r.password,"'.$password.'") as found FROM registereduser r WHERE upper(r.username)=upper("'.$username.'")) as searchUser WHERE searchUser.found = 0)';
      $this->reguser = SqlResultToArray($q,$conn);
      closeSqlConn($conn);
      if($this->reguser != null)
      {
        return true;
      }else {
        return false;
      }
    }

    public static function LogOut()
    {
      startSession();
      unset($_SESSION['student']);
      unset($_SESSION['storemember']);
      header('Location: login.php');
    }
    public function getUserId()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['userid'];
      }
    }
    public function getUsername()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['username'];
      }
    }
    public function getFirstName()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['firstname'];
      }
    }
    public function getLastName()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['lastname'];
      }
    }
    public function getPassword()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['password'];
      }
    }
    public function getEmail()
    {
      if($this->reguser != null)
      {
        return $this->reguser[0]['email'];
      }
    }

    public function getStylePref()
    {
      if($this->reguser != null)
      {
        return (int) $this->reguser[0]['stylePref'];
      }
    }

    public function setStylePref($val) {
      $conn = createSqlConn();
      $q = "UPDATE registereduser SET stylePref=".($val?'1':'0')." WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function getProfilePicturePath() {
      if(!is_dir('users/'.$this->getUserId().'/'))
      {
        return 'icons/profile.png';
      }else {
        return 'users/'.$this->getUserId().'/profile.png';
      }
    }

    public function updateProfile($email,$fname,$lname,$password) {
      $conn = createSqlConn();
      $q = null;
      if(!is_null($password))
      {
        $q = "UPDATE registereduser SET email='".$email."',firstname='".$fname."',lastname='".$lname."', password='".$password."' WHERE userid=".$this->getUserId();
      }else {
        $q = "UPDATE registereduser SET email='".$email."',firstname='".$fname."',lastname='".$lname."' WHERE userid=".$this->getUserId();
      }
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function SearchStore($query, $page, $results_per_page, $isSubCateg) {
      $conn = createSqlConn();
      $limitStart = ($page-1)*$results_per_page;
      $query = realSqlString($query,$conn);
      $searchResultsArr1 = array();
      if(!$isSubCateg)
      {
        $q1 = 'select storeid from store where '.$this->searchCond('name',$query).' or '.$this->searchCond('category',$query);
        $q2 = 'select storeid from discount where discount.expire_date > "'.date('Y-m-d').'" AND ('.$this->searchCond('name',$query).' or '.$this->searchCond('subcategory',$query).')';
        $q_union = $q1.' union '.$q2;
        $searchResultsArr1 = SqlResultToArray($q_union.' LIMIT '.$limitStart.', '.$results_per_page,$conn);
      }else {
        $q = 'select distinct storeid from discount d where d.expire_date > "'.date('Y-m-d').'" AND d.subcategory="'.$query.'"';
        $searchResultsArr1 = SqlResultToArray($q.' LIMIT '.$limitStart.', '.$results_per_page,$conn);
      }
      $searchResultsArr = array();
      foreach ($searchResultsArr1 as $key => $value) {
        array_push($searchResultsArr,new store($value['storeid']));
      }
      closeSqlConn($conn);
      return $searchResultsArr;
    }

    public function getSearchNumberofRows($query, $isSubCatg) {
      $conn = createSqlConn();
      $query = realSqlString($query,$conn);
      $searchResultsArr1 = array();
      if(!$isSubCatg)
      {
        $q1 = 'select storeid from store where '.$this->searchCond('name',$query).' or '.$this->searchCond('category',$query);
        $q2 = 'select storeid from discount where discount.expire_date > "'.date('Y-m-d').'" AND ('.$this->searchCond('name',$query).' or '.$this->searchCond('subcategory',$query).')';
        $q_union = $q1.' union '.$q2;
        $searchResultsArr1 = SqlResultToArray($q_union,$conn);
      }else {
        $q = 'select distinct storeid from discount d where d.expire_date > "'.date('Y-m-d').'" AND d.subcategory="'.$query.'"';
        $searchResultsArr1 = SqlResultToArray($q,$conn);
      }
      closeSqlConn($conn);
      return sizeof($searchResultsArr1);
    }

    private function searchCond($colName, $q) {
      $q = strtolower($q);
      return 'lower('.$colName.') like "'.$q.'%" or lower('.$colName.') like "%'.$q.'" or lower('.$colName.') like "%'.$q.'%"';
    }

    public function removeAccount() {
      $conn = createSqlConn();
      $q = 'DELETE FROM registereduser WHERE userid='.$this->getUserId();
      $result = getQueryResult($q,$conn);
      setNextAvailableAutoIncrement('registereduser','userid');
      closeSqlConn($conn);
      header('location: ../logout.php');
      return true;
    }
  }
  // $regUser1 = new registereduser(1);
  // echo $regUser1->getUserId().'<br />';
  // echo $regUser1->getUsername().'<br />';
  // echo $regUser1->getFirstName().'<br />';
  // echo $regUser1->getLastName().'<br />';
  // echo $regUser1->getPassword().'<br />';
  // echo $regUser1->getEmail().'<br />';
?>
