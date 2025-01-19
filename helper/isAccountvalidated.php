<?php
class IsAccountvalidated{
    private $db;
    private $accountStatus;
    public function __construct($db){
        $this->db = $db;
    }
    public function validateAccount($accountId){
        $query = "SELECT account_status FROM User WHERE id = :accountid";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'accountid' => $accountId
        ]);
        $this->accountStatus = $stmt->fetchColumn();
    }
    public function getAccountStatus(){
        return $this->accountStatus;
    }
}
?>