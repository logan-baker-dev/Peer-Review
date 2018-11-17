<?php
session_start();
include "../includes/config.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_R($_POST);
    echo "<br/>";
    $rosterLoc = saveCSV($_FILES["roster"]);
    if ($rosterLoc) {
        $students = parseCSV($rosterLoc);
        $studentGate = new StudentTableGateway($dbAdapter);
        foreach($students as $student) {
            $student = new Student($student, false);
            echo "<pre>";
            print_r($student);
            echo "</pre>";
            $studentGate->insert($student);
        }
    }
    $evalGate = new EvaluationTableGateway($dbAdapter);
    $eval = array(
        "CourseID" => $_POST["course_id"],
        "CourseTitle" => $_POST["title"],
        "Section" => $_POST["section"],
        "Semester" => $_POST["semester"],
        "Year" => $_POST["year"],
        "InstructorID" => $_SESSION["user"]["InstructorID"]
    );
    $eval = new Evaluation($eval, false);
    $evalGate->insert($eval);
}

function saveCSV($filename) 
{
    $dir = "../uploads/" . $filename["name"] . " " . date("m-d-Y");
    $fileExt = strtolower(pathinfo($filename["name"], PATHINFO_EXTENSION));
    if ($fileExt != "csv") {
        return;
    }
    move_uploaded_file($filename["tmp_name"], $dir);
    return $dir;
}

/*
    CSV format should be [0]LastName, [1]FirstName, [2]Username
*/

function parseCSV($fileLoc) 
{
    $students = array();
    $file = fopen($fileLoc, "r") or die("ERROR OPENING FILE");
    // The first line is the headers
    $headers = fgetcsv($file);
    while (!feof($file)) {
        $row = fgetcsv($file);
        if (!empty($row)) {
            $student = array(
                "FirstName" => $row[1],
                "LastName" => $row[0],
                "Email" => convertToEmail($row[2]),
                "Password" => generatePassword($row)
            );
            array_push($students, $student);
        }
    }
    return $students;
}

function convertToEmail($username)
{
    return (string)($username . "@kent.edu");
}

function generatePassword($row)
{
    return (string)($row[1] . $row[0] . rand(100, 999));
}

?>