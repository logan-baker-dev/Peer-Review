<?php

class StudentTableGateway extends TableDataGateway
{
    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter);
    }

    protected function getDomainObjectClassName()
    {
        return "Student";
    }

    protected function getTableName()
    {
        return "Students";
    }

    protected function getOrderFields()
    {
        return "LastName,FirstName";
    }

    protected function getPrimaryKeyName()
    {
        return "StudentID";
    }

    public function findByEmail($email)
    {
        $sql = $this->getSelectStatement() . ' WHERE Email = :email';
        return $this->convertRowToObject($this->dbAdapter->fetchRow($sql, array(':email' => $email)));
    }
}

?>