<?php
$pageTitle = "Questions";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "nav.php";
$maxdiff = 3;

$query = "SELECT q.id, NameofEdition, Question, Answer, Image, q.Elo from question q INNER JOIN editionofquiz e on e.id=Editionid where q.id not in(Select questionId from questionmeta where username = ?) and e.questionshare = 3 and STR_TO_DATE(CONCAT(e.DateOfQuiz, ' ', e.TimeOfQuiz), '%Y-%m-%d %H:%i:%s') < NOW();";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $Edition, $Question, $Answer, $Image, $Elo);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userAnswer = $_POST['useranswer'];
        $diff = levenshtein($userAnswer, $Answer);
        if($diff < $maxdiff){
            $yen = 1;
            $query = "INSERT INTO questionmeta (username, questionId, result) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $id, $yen);
                mysqli_stmt_execute($stmt);
            }
            header("Location: reply.php?a=1&cor=" . $Answer);
        }else if($diff >= $maxdiff){
            $nul = 0;
            $query = "INSERT INTO questionmeta (username, questionId, result) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $id, $nul);
                mysqli_stmt_execute($stmt);
            }
            header("Location: reply.php?a=0&cor=" . $Answer);
        }

}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row">
        <?php
            if(isset($id)){
                echo '<p>From: ' . $Edition . '</p>
                <img src="qImg/' . $Image . '" alt="" style="max-height: 50%; max-width: 50%; width: auto; height: auto;">
                <p class="pd5">' . $Question . '</p>
                <form method="post">
                    <input type="text" name="useranswer" placeholder="Your Answer" required><br/><br/>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>';
            }else{
                echo '<h2>No more questions at the moment<h2/>';
            }
        ?>
    </div>
  </div>
</main>
</div>
<script>
</script>
<?php
include 'footer.html';
?>