<?php
require 'home/ryanguel/config.php';
class Database {

    private $_dbh;

    public function connect() {
        try {
            $this->_dbh = new PDO(DB_DSN, USERNAME, PASSWORD);
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }

    }

    public function insertMember($member, $f3) {
        $sql = "INSERT INTO member ('fname', 'lname', 'age', 'phone', 'email', 'gender', 'premium') 
        VALUES (:fname, :lname, :age, :phone, :email, :gender, :premium)";
        $statement = $this->_dbh->prepare($sql);

        $statement->bindParam(':fname', $member->getFname(), PDO::PARAM_STR);
        $statement->bindParam(':lname', $member->getLname(), PDO::PARAM_STR);
        $statement->bindParam(':age', $member->getAge(), PDO::PARAM_INT);
        $statement->bindParam(':phone', $member->getPhone(), PDO::PARAM_STR);
        $statement->bindParam(':email', $member->getEmail(), PDO::PARAM_STR);
        $statement->bindParam(':gender', $member->getGender(), PDO::PARAM_STR);
        $statement->bindParam(':premium', $f3->get('member'), PDO::PARAM_STR);

        $statement->execute();
    }

    public function getMembers() {
        $sql = "SELECT * FROM member";
        $statement = $this->_dbh->prepare($sql);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row){
            echo "<p>".$row['member_id']." ".$row['fname']." ".$row['lname']." ".$row['age']." "
                .$row['phone']." ".$row['email']." ".$row['state']." ".$row['gender']." ".$row['seeking']
                ." ".$this->checkPremium($row['premium'])."</p>";
        }
    }
    public function getMember($member_id) {
        $sql = "SELECT * FROM member WHERE member_id = $member_id";
        $statement = $this->_dbh->prepare($sql);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo "<p>".$result['fname']." ".$result['lname']." ".$result['age']." ".$result['phone']." "
            .$result['email']." ".$result['state']." ".$result['gender']." ".$result['seeking']." "
            .$result['bio']." ".$this->getInterests($result['member_id'])."</p>";
    }

    public function getInterests($member_id) {

    }

    private function checkPremium($row) {
            if($row['premium'] == 1){
                return ;
            }
    }
}