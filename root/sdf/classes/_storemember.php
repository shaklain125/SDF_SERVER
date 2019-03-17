<?php
  /**
   *
   */
  class storemember extends registereduser
  {
    function __construct($userid)
    {
      parent::__construct($userid);
    }

    public function AddStore($storename,$storedescr,$storephone,$storewebsite,$storecateg)
    {
      $conn = createSqlConn();
      $userid = $this->getUserId();
      $storename = realSqlString($storename,$conn);
      $storedescr = realSqlString($storedescr,$conn);
      $storephone = realSqlString($storephone,$conn);
      $storewebsite = realSqlString($storewebsite,$conn);
      $storecateg = realSqlString($storecateg,$conn);
      $q1 = "INSERT INTO store (name,description,phone,website,category,storememberID) VALUES ('$storename','$storedescr','$storephone','$storewebsite','$storecateg','$userid')";
      if(getQueryResult($q1,$conn))
      {
        $last_id = $conn->insert_id;
        return $this->setStores($last_id, $conn);
      }
      closeSqlConn($conn);
      return false;
    }

    public function getStores()
    {
      return findRowInTable('userid',$this->getUserId(),'storemember')[0]['stores'];
    }

    function setStores($storeid, $conn)
    {
      $stores = $this->getStores();
      if($stores != null)
      {
        $stores = unserialize($stores);
        array_push($stores,$storeid);
      }else {
        $stores = array($storeid);
      }
      $q = "UPDATE storemember SET stores='".serialize($stores)."' WHERE userid=".$this->getUserId();
      return getQueryResult($q,$conn);
    }

    public function removeStoreL($storeid, $conn)
    {
      $stores = $this->getStores();
      if($stores != null)
      {
        $stores = unserialize($stores);
        $indx = $this->searchStoreid($stores,$storeid);
        if($indx != -1)
        {
          unset($stores[$indx]);
          $q = "UPDATE storemember SET stores='".serialize($stores)."' WHERE userid=".$this->getUserId();
          return getQueryResult($q,$conn);
        }else {
          return -1;
        }
      }else {
        return null;
      }
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

    public function AddDiscount($storeid, $discountName, $discountPercent, $discountStartD,$discountEndD,$discountSubCateg, $codeLength)
    {
      $code = $this->generateCode($codeLength);
      $conn = createSqlConn();
      $userid = $this->getUserId();
      $q1 = "INSERT INTO discount (name,percent,expire_date,start_date,subcategory,storeid) VALUES ('$discountName', '$discountPercent','$discountEndD', '$discountStartD','$discountSubCateg','$storeid')";
      if(getQueryResult($q1,$conn))
      {
        $last_id = $conn->insert_id;
        return $this->setGeneratedCode($last_id,$conn,$code);
      }
      closeSqlConn($conn);
      return false;
    }

    private function setGeneratedCode($last_id, $conn, $code)
    {
      $q = "UPDATE discount SET code='".$code."' WHERE discountid=".$last_id;
      return getQueryResult($q,$conn);
    }

    private function generateCode($len)
    {
      $conn = createSqlConn();
      $q1 = SqlResultToArray("select code from discount",$conn);
      if(sizeof($q1) > 0)
      {
        $q2 = "SELECT concat(".$this->codeLength($len).") as generatedCode FROM discount WHERE 'generatedCode' NOT IN (SELECT code FROM discount) LIMIT 1";
      }else {
        $q2 = "SELECT concat(".$this->codeLength($len).") as generatedCode";
      }
      $code = SqlResultToArray($q2,$conn)[0]['generatedCode'];
      closeSqlConn($conn);
      return $code;
    }

    private function codeLength($len)
    {
      $code = str_repeat("char(round(rand()*25)+65),",$len);
      return rtrim($code,',');
    }

    public function DeleteDiscount($discountid)
    {
      $conn = createSqlConn();
      $q = "DELETE FROM discount where discountid=".$discountid;
      $result = getQueryResult($q,$conn);
      setNextAvailableAutoIncrement('discount','discountid');
      closeSqlConn($conn);
      return $result;
    }

    public function EditStorePage($storeid, $storename,$storedescr,$storephone,$storewebsite,$storecateg)
    {
      $conn = createSqlConn();
      $storename = realSqlString($storename,$conn);
      $storedescr = realSqlString($storedescr,$conn);
      $storephone = realSqlString($storephone,$conn);
      $storewebsite = realSqlString($storewebsite,$conn);
      $storecateg = realSqlString($storecateg,$conn);
      $q = "UPDATE store SET name='".$storename."', description='".$storedescr."', phone='".$storephone."', website='".$storewebsite."', category='".$storecateg."' WHERE storeid=".$storeid;
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function RemoveStore($storeid) {
      $conn = createSqlConn();
      $q = "DELETE FROM discount WHERE storeid=".$storeid;
      if(getQueryResult($q,$conn))
      {
        $q = "DELETE FROM store WHERE storeid=".$storeid;
        if(getQueryResult($q,$conn))
        {
          setNextAvailableAutoIncrement('store','storeid');
          setNextAvailableAutoIncrement('discount','discountid');
          $this->removeStoreL($storeid, $conn);
        }
      }
      closeSqlConn($conn);
    }

    public function RemoveAllStores() {
      $conn = createSqlConn();
      $q1 = "SELECT storeid FROM store WHERE storememberID=".$this->getUserId();
      $stores = SqlResultToArray($q1,$conn);
      foreach ($stores as $key => $value) {
        $storeid = $value['storeid'];
        $q = "DELETE FROM discount WHERE storeid=".$storeid;
        if(getQueryResult($q,$conn))
        {
          $q = "DELETE FROM store WHERE storeid=".$storeid;
          getQueryResult($q,$conn);
          $this->removeStoreL($storeid, $conn);
        }
      }
      setNextAvailableAutoIncrement('store','storeid');
      setNextAvailableAutoIncrement('discount','discountid');
      closeSqlConn($conn);
    }

    public function removeStoreMemberAccount() {
      $conn = createSqlConn();
      $q = 'DELETE FROM storemember WHERE userid='.$this->getUserId();
      $result = getQueryResult($q, $conn);
      closeSqlConn($conn);
      parent::removeAccount();
    }
  }
?>
