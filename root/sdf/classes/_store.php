<?php
  /**
   *
   */
  class store
  {
    private $storePage;
    function __construct($storeid)
    {
      $conn = createSqlConn();
      $this->storePage = SqlResultToArray('select * from store where storeid='.$storeid,$conn);
      closeSqlConn($conn);
    }

    public function isNull()
    {
      return $this->storePage == null;
    }

    public function getStoreId()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['storeid'];
      }
    }

    public function getName()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['name'];
      }
    }

    public function getDescription()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['description'];
      }
    }

    public function getCategory()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['category'];
      }
    }

    public function getWebsite()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['website'];
      }
    }

    public function getStorememberId()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['storememberID'];
      }
    }

    public function getPhone()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['phone'];
      }
    }

    public function getLikes()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['likes'];
      }
    }

    public function getDislikes()
    {
      if(!$this->isNull())
      {
        return $this->storePage[0]['dislikes'];
      }
    }

    public function getDiscounts()
    {
      if(!$this->isNull())
      {
        $conn = createSqlConn();
        $q1 = "select discountid from discount where storeid='".$this->getStoreId()."' AND discount.expire_date > '".date('Y-m-d')."'";
        $discountRows = SqlResultToArray($q1, $conn);
        closeSqlConn($conn);
        $discounts = array();
        foreach ($discountRows as $key => $value) {
          $newD = new discount($value['discountid']);
          if(!$newD->isDiscountExpired())
          {
            array_push($discounts,$newD);
          }
        }
        return $discounts;
      }
    }
    public function GetLatestDiscount($query) {
      if(empty($query))
      {
        return array();
      }
      $d = $this->getDiscounts();
      $d = array_reverse($d);
      foreach ($d as $key => $value) {
        if(strpos(strtolower($value->getSubCategory()),strtolower($query)) === false && strpos(strtolower($value->getName()),strtolower($query)) === false)
        {
          continue;
        }else {
          if(!$value->isDiscountExpired())
          {
            return $value;
          }else {
            continue;
          }
        }
      }
      return $d != null? $d[0] : null;
    }

    public function getStorePhotoPath() {
      if(!is_dir('stores/'.$this->getStoreId().'/'))
      {
        return 'icons/store.jpg';
      }else {
        return 'stores/'.$this->getStoreId().'/store.jpg';
      }
    }
  }
?>
