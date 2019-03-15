<?php
  /**
   *
   */
  class discount
  {
    private $d_;
    function __construct($discountid)
    {
      $conn = createSqlConn();
      $this->d_ = SqlResultToArray('select * from discount where discountid='.$discountid,$conn);
      closeSqlConn($conn);
    }

    public function isNull() {
      return $this->d_ == null;
    }

    public function getDiscountId() {
      if(!$this->isNull()){
        return $this->d_[0]['discountid'];
      }
    }

    public function getStoreId() {
      if(!$this->isNull()){
        return $this->d_[0]['storeid'];
      }
    }

    public function getPercent() {
      if(!$this->isNull()){
        return $this->d_[0]['percent'];
      }
    }
    public function getName() {
      if(!$this->isNull()){
        return $this->d_[0]['name'];
      }
    }
    public function getCode() {
      if(!$this->isNull()){
        return $this->d_[0]['code'];
      }
    }
    public function getExpireDate() {
      if(!$this->isNull()){
        return $this->d_[0]['expire_date'];
      }
    }
    public function getStartDate() {
      if(!$this->isNull()){
        return $this->d_[0]['start_date'];
      }
    }
    public function getSubCategory() {
      if(!$this->isNull()){
        return $this->d_[0]['subcategory'];
      }
    }

    public function isDiscountExpired() {
      $expire = $this->getExpireDate();
      $expire = new DateTime($expire);
      $today = new DateTime(date('Y-m-d'));
      $daysLeft = $today->diff($expire);
      //$expire->format('Y-m-d')
      // $s = $today->format('Y-m-d').' -- '.$expire->format('Y-m-d').' --> '.$daysLeft->format('%R%a').'days left --> '.(((int)$daysLeft->format('%R%a') <= 0)?'expired':'available');
      // echo $s.'<br />';
      return ((int) $daysLeft->format('%R%a')) <= 0;
    }
  }
?>
