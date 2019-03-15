<?php
/**
 *
 */
  class studentclaim
  {
    private $stclaim;
    function __construct($studentclaimid)
    {
      $conn = createSqlConn();
      $this->stclaim = SqlResultToArray('select * from studentclaim where studentclaim_id='.$studentclaimid,$conn);
      closeSqlConn($conn);
    }

    public function getStudentClaimID() {
      return $this->stclaim[0]['studentclaim_id'];
    }

    public function getDiscountID() {
      return $this->stclaim[0]['discountid'];
    }

    public function isDiscountAvailable() {
      $findDiscount = findRowInTable('discountid',$this->getDiscountId(),'discount');
      $conn = createSqlConn();
      $available = '1';
      if($findDiscount == null)
      {
        $available = '0';
      }
      $q = "UPDATE studentclaim SET discount_available='".$available."' WHERE studentclaim_id=".$this->getStudentClaimID();
      $result = getQueryResult($q,$conn);
      $this->stclaim = SqlResultToArray('select * from studentclaim where studentclaim_id='.$this->getStudentClaimID(),$conn);
      closeSqlConn($conn);
      return $this->stclaim[0]['discount_available'] == '1'?true:false;
    }

    public function getDiscountRating() {
      $r = $this->stclaim[0]['discount_rating'];
      if($r == '1')
      {
        return '1';
      }elseif ($r == '0') {
        return '0';
      }else {
        return '-1';
      }
    }

    public function isUsed() {
      return $this->stclaim[0]['used'] == '1'? true : false;
    }

    public function isDiscountRated() {
      $r = $this->stclaim[0]['discount_rating'];
      return $r == '1' || $r == '0'? true : false;
    }

    public function UseCode() {
      $conn = createSqlConn();
      $q = "UPDATE studentclaim SET used='1' WHERE studentclaim_id=".$this->getStudentClaimID();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function setDiscountRating($bool) {
      $conn = createSqlConn();
      $currentrating = $this->getDiscountRating();
      $bool = $bool? '1':'0';
      if($currentrating != '-1')
      {
        if($currentrating)
        {
          if($bool == '1')
          {
            $bool = 'NULL';
          }else {
            $bool = '0';
          }
        }else {
          if($bool == '1')
          {
            $bool = '1';
          }else {
            $bool = 'NULL';
          }
        }
      }
      $q = "UPDATE studentclaim SET discount_rating=".$bool." WHERE studentclaim_id=".$this->getStudentClaimID();
      $result = getQueryResult($q,$conn);
      closeSqlConn($conn);
      return $result;
    }

    public function getDiscount() {
      if($this->isDiscountAvailable())
      {
        return new discount($this->stclaim[0]['discountid']);
      }else {
        return unserialize($this->stclaim[0]['discount'])[0];
      }
    }

    public function getStoreName() {
      return unserialize($this->stclaim[0]['discount'])[1];
    }

    public function isDiscountExpired() {
      if($this->isDiscountAvailable())
      {
        $d = new discount($this->stclaim[0]['discountid']);
        $expire = $d->getExpireDate();
        $expire = new DateTime($expire);
        $today = new DateTime(date('Y-m-d'));
        $daysLeft = $today->diff($expire);
        //$expire->format('Y-m-d')
        // $s = $today->format('Y-m-d').' -- '.$expire->format('Y-m-d').' --> '.$daysLeft->format('%R%a').'days left --> '.(((int)$daysLeft->format('%R%a') <= 0)?'expired':'available');
        return ((int) $daysLeft->format('%R%a')) <= 0;
      }else {
        return true;
      }
    }
  }

?>
