<?php
  /**
   *
   */
  class student extends registereduser
  {
    private $regstudent;
    function __construct($userid)
    {
      parent::__construct($userid);
      $conn = createSqlConn();
      $this->regstudent = SqlResultToArray('select * from student where userid='.$userid,$conn);
      closeSqlConn($conn);
    }
    public function getDob()
    {
      return $this->regstudent[0]['dob'];
    }
    public function getGradDate()
    {
      return $this->regstudent[0]['graduation_date'];
    }
    public function getUniversity()
    {
      return $this->regstudent[0]['university'];
    }
    public function getPrefCategories() {
      return $this->regstudent[0]['prefCategories'] == null ? serialize(array()) :$this->regstudent[0]['prefCategories'];
    }
    public function getPrefSubCategories() {
      return $this->regstudent[0]['prefSubCategories'] == null ? serialize(array()) :$this->regstudent[0]['prefSubCategories'];
    }
    public function updateStudentProfile($email,$fname,$lname,$password,$dob,$university,$gradD,$prefC, $prefSc) {
      parent::updateProfile($email,$fname,$lname,$password);
      $conn = createSqlConn();
      $q = "UPDATE student SET dob='".$dob."', university='".$university."',graduation_date='".$gradD."', prefCategories='".serialize($prefC)."', prefSubCategories='".serialize($prefSc)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function getLikedStores($liked)
    {
      $s = findRowInTable('userid',$this->getUserId(),'student')[0];
      $stores = null;
      if($liked)
      {
        $stores = $s['fav_stores'];
        if($stores != null)
        {
          $stores = $this->removeNotFoundStoresFromList('fav_stores',unserialize($stores));
        }
      }else {
        $stores = $s['disliked_stores'];
        if($stores != null)
        {
          $stores = $this->removeNotFoundStoresFromList('disliked_stores',unserialize($stores));
        }
      }
      return $stores;
    }

    public function getStoreHistory()
    {
      $s = findRowInTable('userid',$this->getUserId(),'student')[0];
      $stores = $s['store_view_history'];
      if($stores != null)
      {
        $stores = $this->removeNotFoundStoresFromList('store_view_history',unserialize($stores));
      }
      return $stores;
    }

    public function ToggleLikes($storeid) {
      $conn = createSqlConn();
      $favStores = $this->getLikedStores(true);
      $dislikedStores = $this->getLikedStores(false);
      $store = findRowInTable('storeid',$storeid,'store')[0];
      $likes = $store['likes'];
      $dislikes = $store['dislikes'];
      if($favStores != null)
      {
        $favStores = $favStores;
        $search = $this->searchStoreid($favStores, $storeid);
        if($search == -1)
        {
          array_push($favStores,$storeid);
          $likes++;
        }else {
          unset($favStores[$search]);
          $likes--;
        }
      }else {
        $favStores = array($storeid);
        $likes++;
      }
      if($dislikedStores != null)
      {
        $dislikedStores = $dislikedStores;
        $search = $this->searchStoreid($dislikedStores, $storeid);
        if($search != -1)
        {
          unset($dislikedStores[$search]);
          $dislikes--;
        }
      }else {
        $dislikedStores = array();
      }
      $q = "UPDATE student SET fav_stores='".serialize($favStores)."', disliked_stores='".serialize($dislikedStores)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      $q1 = "UPDATE store SET likes=".$likes.", dislikes=".$dislikes." WHERE storeid=".$storeid;
      $result1 = getQueryResult($q1,$conn);
      closeSqlConn($conn);
      return $result && $result1;
      // return null;
    }


    public function ToggleDisLikes($storeid) {
      $conn = createSqlConn();
      $favStores = $this->getLikedStores(true);
      $dislikedStores = $this->getLikedStores(false);
      $store = findRowInTable('storeid',$storeid,'store')[0];
      $likes = $store['likes'];
      $dislikes = $store['dislikes'];
      if($dislikedStores != null)
      {
        $dislikedStores = $dislikedStores;
        $search = $this->searchStoreid($dislikedStores, $storeid);
        if($search == -1)
        {
          array_push($dislikedStores,$storeid);
          $dislikes++;
        }else {
          unset($dislikedStores[$search]);
          $dislikes--;
        }
      }else {
        $dislikedStores = array($storeid);
        $dislikes++;
      }
      if($favStores != null)
      {
        $favStores = $favStores;
        $search = $this->searchStoreid($favStores, $storeid);
        if($search != -1)
        {
          unset($favStores[$search]);
          $likes--;
        }
      }else {
        $favStores = array();
      }
      $q = "UPDATE student SET fav_stores='".serialize($favStores)."', disliked_stores='".serialize($dislikedStores)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      $q1 = "UPDATE store SET likes=".$likes.", dislikes=".$dislikes." WHERE storeid=".$storeid;
      $result1 = getQueryResult($q1,$conn);
      if(!($result && $result1))
      {
        echo $conn->error;
      }
      return $result && $result1;
    }

    private function searchStoreid($arr, $storeid) {
      foreach ($arr as $key => $value) {
        if($value == $storeid)
        {
          return $key;
        }
      }
      return -1;
    }

    public function hasLikedStore($storeid) {
      $favStores = $this->getLikedStores(true);
      if($favStores != null)
      {
        if($this->searchStoreid($favStores,$storeid) == -1)
        {
          return false;
        }else {
          return true;
        }
      }else {
        return false;
      }
    }

    public function hasDislikedStore($storeid) {
      $dislikedStores = $this->getLikedStores(false);
      if($dislikedStores != null)
      {
        if($this->searchStoreid($dislikedStores,$storeid) == -1)
        {
          return false;
        }else {
          return true;
        }
      }else {
        return false;
      }
    }

    public function addStoreToHistory($storeid) {
      $conn = createSqlConn();
      $storehistory = $this->getStoreHistory();
      if($storehistory != null)
      {
        $storehistory = $storehistory;
        $search = $this->searchStoreid($storehistory, $storeid);
        if($search != -1)
        {
          unset($storehistory[$search]);
        }
        array_push($storehistory,$storeid);
      }else {
        $storehistory = array($storeid);
      }
      $q = "UPDATE student SET store_view_history='".serialize($storehistory)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function removeStoreFromHistory($storeid) {
      $conn = createSqlConn();
      $storehistory = $this->getStoreHistory();
      if($storehistory != null)
      {
        $storehistory = $storehistory;
        $search = $this->searchStoreid($storehistory, $storeid);
        if($search != -1)
        {
          unset($storehistory[$search]);
        }
      }else {
        $storehistory = array();
      }
      $q = "UPDATE student SET store_view_history='".serialize($storehistory)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function StoreExists($storeid) {
      $store = findRowInTable('storeid',$storeid,'store');
      return $store != null;
    }

    private function removeNotFoundStoresFromList($slistName, $stores) {
      $s = $stores;
      if($s == null)
      {
        return array();
      }
      foreach ($s as $key => $value) {
        if(!$this->StoreExists($value))
        {
          unset($stores[$key]);
        }
      }
      $conn = createSqlConn();
      $q = "UPDATE student SET ".$slistName."='".serialize($stores)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $stores;
    }

    public function clearStoreHistory() {
      $conn = createSqlConn();
      $storehistory = array();
      $q = "UPDATE student SET store_view_history='".serialize($storehistory)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function getClaimedList()
    {
      return findRowInTable('userid',$this->getUserId(),'student')[0]['claimedlist'];
    }

    public function getStudentClaims() {
      $claimedL = $this->getClaimedList();
      $sclaims = array();
      if($claimedL != null)
      {
        $claimedL = unserialize($claimedL);
        foreach ($claimedL as $key => $value) {
          array_push($sclaims, new studentclaim($value));
        }
      }
      return $sclaims;
    }

    public function claimDiscount($discountid) {
      $conn = createSqlConn();
      $claimedL = $this->getClaimedList();
      if($claimedL != null)
      {
        $claimedL = unserialize($claimedL);
        foreach ($claimedL as $key => $value) {
          $sc = new studentclaim($value);
          if($sc->getDiscountId() == $discountid)
          {
            return null;
          }
        }
      }
      $discount = new discount($discountid);
      $storename = (new store($discount->getStoreId()))->getName();
      $d = serialize(array($discount,$storename));
      $q1 = "INSERT INTO studentclaim (discount, discountid) VALUES ('$d','$discountid')";
      if(getQueryResult($q1,$conn))
      {
        $last_id = $conn->insert_id;
        return $this->setClaimedList($last_id, $conn);
      }
      closeSqlConn($conn);
      return false;
    }

    private function setClaimedList($stclaimID, $conn) {
      $claimedL = $this->getClaimedList();
      if($claimedL != null)
      {
        $claimedL = unserialize($claimedL);
        array_push($claimedL,$stclaimID);
      }else {
        $claimedL = array($stclaimID);
      }
      $q = "UPDATE student SET claimedlist='".serialize($claimedL)."' WHERE userid=".$this->getUserId();
      return getQueryResult($q,$conn);
    }

    public function setClaimedListArray($claimedL, $studentClaimIds) {
      $conn = createSqlConn();
      $q = "UPDATE student SET claimedlist='".serialize($claimedL)."' WHERE userid=".$this->getUserId();
      $result = getQueryResult($q,$conn);
      foreach ($studentClaimIds as $key => $value) {
        $this->removeStudentClaim($value,$conn);
      }
      closeSqlConn($conn);
    }

    private function removeStudentClaim($studentclaimid, $conn) {
      $q = "DELETE FROM studentclaim WHERE studentclaim_id=".$studentclaimid;
      return getQueryResult($q, $conn);
    }

    public function isDiscountIDInClaimedList($discountid) {
      $claimedL = $this->getClaimedList();
      if($claimedL != null)
      {
        $claimedL = unserialize($claimedL);
        foreach ($claimedL as $key => $value) {
          $sc = new studentclaim($value);
          if($sc->getDiscountId() == $discountid)
          {
            return true;
          }
        }
      }
      return false;
    }

    public function isClaimedDiscountUsed($studentclaimid) {
      $studentclaim = new studentclaim($studentclaimid);
      return $studentclaim->isUsed();
    }

    public function rateClaimedDiscount($studentclaimid, $bool) {
      $studentClaim = new studentclaim($studentclaimid);
      $studentClaim->setDiscountRating($bool);
    }

    public function removeStudentAccount() {
      $sclaims = $this->getStudentClaims();
      $conn = createSqlConn();
      foreach ($sclaims as $key => $value) {
        $q = 'DELETE FROM studentclaim WHERE studentclaim_id='.$value->getStudentClaimID();
        if(getQueryResult($q, $conn))
        {
          setNextAvailableAutoIncrement('studentclaim','studentclaim_id');
        }
      }
      $q = 'DELETE FROM student WHERE userid='.$this->getUserId();
      $result = getQueryResult($q, $conn);
      closeSqlConn($conn);
      parent::removeAccount();
    }

  }
  // $regUser1 = new student(1);
  // echo $regUser1->getUserId().'<br />';
  // echo $regUser1->getUsername().'<br />';
  // echo $regUser1->getFirstName().'<br />';
  // echo $regUser1->getLastName().'<br />';
  // echo $regUser1->getPassword().'<br />';
  // echo $regUser1->getEmail().'<br />';
  // echo $regUser1->getDob().'<br />';
  // echo $regUser1->getGradDate().'<br />';
  // echo $regUser1->getUniversity().'<br />';
?>
