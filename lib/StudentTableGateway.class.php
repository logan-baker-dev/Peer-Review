<?php

class StudentTableGateway extends TableDataGateway
{
    public function __construct($dbAdapter)
    {
        parent::__construct($dbAdapter);
    }

    protected function getTableName()
    {
        return "students";
    }

    protected function getDomainObjectClassName()
    {
        return "Student";
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
        $sql = $this->getSelectStatement() . " WHERE Email = ?";
        return $this->convertRowToObject($this->dbAdapter->fetchRow($sql, $email));
    }

    public function findByGroupID($groupID)
    {
        $sql = $this->getSelectStatement() . " WHERE GroupID = ?";
        return $this->convertRecordsToObjects($this->dbAdapter->fetchAsArray($sql, $groupID));
    }

    public function findByEvalID($evalID)
    {
        $sql = $this->getSelectStatement() . " WHERE EvaluationID = ?";
        return $this->convertRecordsToObjects($this->dbAdapter->fetchAsArray($sql, $evalID));
    }

    public function setGroupID($studentID, $id) 
    {
        $sql = "UPDATE " . $this->getTableName() . " SET GroupID = ? WHERE StudentID = ?";
        $this->dbAdapter->runQuery($sql, Array($id, $studentID));
    }

    public function setCompletedEval($studentID, $status) 
    {
        $sql = "UPDATE " . $this->getTableName() . " SET CompletedEval = ? WHERE StudentID = ?";
        $this->dbAdapter->runQuery($sql, Array($status, $studentID));
    }

    public function updateEvalID($student)
    {
        $fieldsToUpdate = array("EvaluationID" => $student->EvaluationID,
                                "GroupID" => null,
                                "CompletedEval" => false);
        $this->dbAdapter->update($this->getTableName(), $fieldsToUpdate,
                                 "StudentID = :studentID",
                                 array(":studentID" => $student->StudentID));
    }
}

?>