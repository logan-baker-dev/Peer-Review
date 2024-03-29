<?php
include '../includes/helpers.inc.php';
session_start();

if (!empty($_SESSION['user'])) {
    if ($_SESSION['user']['admin']) {
        header('Location: ../admin/index.php');
        exit();
    }
    else {
        $userInDB = getUserInfo("Student", $_SESSION["user"]["Email"]);
        if (!empty($userInDB)) {
            $_SESSION["user"] = $userInDB;
            $_SESSION["user"]["admin"] = false;
        }
        else {
            session_destroy();
            header("Location: ../login.php");
            exit();
        }
    }
}
else {
    header("Location: ../login.php");
    exit();
}

if (!$_SESSION["user"]["GroupID"]) {
    header("Location: index.php");
    exit();
}

if ($_SESSION["user"]["CompletedEval"]) {
    header("Location: grade.php");
    exit();
}

$groupGate = new GroupTableGateway($dbAdapter);
$studentGate = new StudentTableGateway($dbAdapter);
$criteriaGate = new GradeCriteriaTableGateway($dbAdapter);
$gradeGate = new GradeTableGateway($dbAdapter);
$commentGate = new CommentTableGateway($dbAdapter);

$group = $groupGate->findById($_SESSION["user"]["GroupID"]);
$students = $studentGate->findByGroupID($group->GroupID);
$criterias = $criteriaGate->findAll();
$graded = $gradeGate->findByGraderID($_SESSION["user"]["StudentID"]);
$comment = $commentGate->findUniqueComment($_SESSION["user"]["StudentID"], $_SESSION["user"]["EvaluationID"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en">
    <title>Evaluation Page</title>
    <?php insertLinks(); ?>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php insertNavbar(); ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 table-responsive-md">
                <h2 class="text-center mb-4">Evaluations</h2>
                <h4><strong>Project Name:</strong> <?= $group->ProjectName ?></h4>
                <p><strong>Project Description:</strong> <?= $group->ProjectDescription ?></p>
                <form class="needs-validation" action="../lib/GradeValidator.php" method="post" novalidate>
                    <p class="d-inline-block"><strong>* You must enter values between 0-10.</strong></p>
                    <?php if (!empty($_SESSION["errors"]["input"])) { ?>
                    <p class="form-alert mb-2">* <?= $_SESSION["errors"]["input"] ?></p>
                    <?php } ?>
                    <?php $gradeValueIndex = 0 ?>
                    <?php foreach($students as $student) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <?php if ($student->Email == $group->LeaderEmail) { ?>
                                    <?php if ($student->StudentID == $_SESSION["user"]["StudentID"]) { ?>
                                        <th style="background: rgba(0,0,0,0.1); font-style: italic;" colspan="<?= count($criterias) ?>"><i class="fas fa-crown"></i> <?= $student->getFullName(); ?> ( Group Leader ) ( You )</th>
                                    <?php   } else { ?>
                                        <th colspan="<?= count($criterias) ?>"><i class="fas fa-crown"></i> <?= $student->getFullName(); ?> ( Group Leader )</th>
                                    <?php } ?>
                                    <?php } else if ($student->StudentID == $_SESSION["user"]["StudentID"]) { ?>
                                    <th style="background: rgba(0,0,0,0.1); font-style: italic;" colspan="<?= count($criterias) ?>">* <?= $student->getFullName(); ?> ( You )</th>
                                    <?php } else { ?>
                                    <th colspan="<?= count($criterias) ?>"><?= $student->getFullName(); ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php foreach($criterias as $criteria) { ?>
                                    <td><?= $criteria->Title ?></td>
                                <?php } ?>
                                </tr>
                                <?php foreach($criterias as $criteria) { ?>
                                    <td><small><?= $criteria->Description ?></small></td>
                                <?php } ?>
                                </tr>
                                <tr>
                                <?php foreach($criterias as $criteria) { ?>
                                <?php if (!empty($_SESSION["errors"]["input"])) { ?>
                                    <td>
                                    <?php if (!empty($_SESSION["grades"][$gradeValueIndex])) { ?>
                                        <input class="form-control is-valid" type="number" 
                                            name="<?= $student->StudentID ?>[<?= $criteria->Title ?>]" 
                                            min="0" max="10" value="<?= $_SESSION["grades"][$gradeValueIndex] ?>">
                                    <?php } else { ?>
                                        <input class="form-control is-invalid" type="number" 
                                            name="<?= $student->StudentID ?>[<?= $criteria->Title ?>]" 
                                            min="0" max="10">
                                    <?php } ?>
                                    </td>
                                    <?php $gradeValueIndex++; ?>
                                    <?php } else if (!$_SESSION["user"]["CompletedEval"]) { ?>
                                    <?php if (!empty($graded)) { ?>
                                        <?php foreach($graded as $grade) { ?>
                                            <?php $title = (string)$criteria->Title; ?>
                                    <td>
                                        <input class="form-control" type="number" 
                                            name="<?= $student->StudentID ?>[<?= $criteria->Title ?>]" 
                                            min="0" max="10" value="<?= $grade->$title ?>">
                                      <?php } ?>
                                    <?php } else { ?>
                                    <td>
                                        <input class="form-control" 
                                                type="number" name="<?= $student->StudentID ?>[<?= $criteria->Title ?>]" 
                                                min="0" max="10">
                                    </td>
                                    <?php } ?>
                                <?php } ?>
                      <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                    <div class="form-group text-center">
                        <h5>Comments or Explanation for grades</h5>
                        <h5>(Optional)<h5>
                        <?php if (!empty($comment)) { ?>
                        <textarea class="form-control" name="comment" cols="100%" rows="10"><?= $comment->Comments ?></textarea>
                        <?php } else { ?>
                        <textarea class="form-control" name="comment" cols="100%" rows="10"></textarea>
                        <?php } ?>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-lg btn-block btn-dark mt-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>