<?php
$pageTitle = "Edition of quiz";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
if($_SESSION['level'] == 0){
    include "nav.php";
}else if($_SESSION['level'] == 1){
    include "qnav.php";
}
include "hexToRgba.php";

$id = $_GET['id'];

$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $themes[] = $row['Name'];
    }
}

$query = "SELECT e.id, e.NameOfEdition, e.QuizId, e.TimeOfQuiz, e.DateOfQuiz, e.ThemeOfQuiz, e.Location, e.Elo, e.RegFee, e.Awards, e.MaxPlayer, e.MaxTeams, e.QuestionShare, e.nQuestions , (SELECT COUNT(teamID) FROM teamonquiz where quizid = e.id) AS NoTeams, Picture, Color FROM editionofquiz e  inner join quiz q on q.id = e.quizid WHERE e.id = ?;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfEdition, $QuizId, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions, $NoTeams, $Picture, $Color);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
$rew = explode('|', $Awards);

$TeamIds = array();
$query = "SELECT tp.id, tp.one, tp.two, tp.three, tp.four, tp.five, tp.Elo, answers, (SELECT NameOfTeam FROM teams t WHERE t.id = tq.teamId) FROM teamplayers tp INNER JOIN teamonquiz tq ON tp.id = tq.id WHERE tp.id IN (SELECT id FROM teamonquiz WHERE quizid = ?) ORDER BY LENGTH(REPLACE(answers, '0', '')) DESC;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $teamId, $one, $two, $three, $four, $five, $telo, $answers, $teamName);
    while (mysqli_stmt_fetch($stmt)) {
        $teammember = $one;
        if ($two !== null) {
            $teammember .= '|' . $two;
        }
        if ($three !== null) {
            $teammember .= '|' . $three;
        }
        if ($four !== null) {
            $teammember .= '|' . $four;
        }
        if ($five !== null) {
            $teammember .= '|' . $five;
        }
        $TeamElos[] = $telo;
        $TeamIds[] = $teamId;
        $TeamAnswers[] = $answers;
        $TeamNames[] = $teamName;
        $TeamMembers[] = $teammember;
    }
    mysqli_stmt_close($stmt);
}

$onquiz = 0;
if($QuestionShare == 3){
    $onquiz = 1;
}else if($QuestionShare == 2){
    for ($i = 0; $i < count($TeamMembers); $i++) {
        $players = explode('|', $TeamMembers[$i]);
        for ($j = 0; $j < count($players); $j++) {
            if($players[$j] == $_SESSION['username']){
                $onquiz = 1;
            }
        }
    }
}
if($_SESSION['level'] == 1 && $_SESSION['id'] == $QuizId){
    $onquiz = 1;
}

if($onquiz == 1){
    $query = "SELECT question, answer, image FROM question WHERE editionid = ? order by round, id; ";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $question, $answer, $image);
        while (mysqli_stmt_fetch($stmt)) {
            $questions[] = $question;
            $answersl[] = $answer;
            $images[] = $image;
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<style>
    body {
        background-color: <?php echo $Color; ?>;
    }
</style>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%; background-color: <?php echo $Color; ?>;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row" style="padding: 0%; border-radius: 5% 5% 0% 0%;">
    <?php echo '<div class="pd5 br5" style="background-color: ' . hexToRgba($Color) . ';  border-radius: 5% 5% 0% 0%;"><img class="ppp" src="' . $Picture . '"><h4 class="nameofquiz">' . $NameOfEdition . '</h4><b>Category</b><p>' . $themes[$ThemeOfQuiz] . '</h4><b>Time of quiz</b><p>' . $DateOfQuiz . '<br/>' . $TimeOfQuiz . '</p><b>Location</b><p>' . $Location . '</p><b>Elo</b><p>' . $Elo . '</p><b>Registration fee</b><p>' . $RegFee . '</p><b>Awards</b><br/><p>1st: ' . $rew[0] . '<br/>2nd: ' . $rew[1] . '<br/>3rd: ' . $rew[2] . '</p><b>Teams</b><p>' . $NoTeams . '/' . $MaxTeams . '</p></div>'; ?>
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
    <h4 class="nameofquiz">Teams on quiz</h4>
        <table>
        <?php
            for ($i = 0; $i < count($TeamIds); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;"><b>' . $i+1 . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $TeamNames[$i] . '</b><br/><p><b>Elo: </b>' . $TeamElos[$i] . '</p></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;"><b>Score: <br/></b>' . substr_count($TeamAnswers[$i], "1") . ' / ' . strlen($TeamAnswers[$i]) . '</p></div>
                        </td>
                </tr>';
            }
            ?>
        </table>
        <?php
        if($onquiz == 1){
            echo '<h4 class="nameofquiz" style="padding-top: 10%">Questions</h4>
                <table>';
            for ($i = 0; $i < count($questions); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $i+1 . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><img src="qImg/' . $images[$i] . '" class="ppp" alt="" style="float : left"><p style="float : left">' . $questions[$i] . '</p><br/><b style="float : left; clear: both;">' . $answersl[$i] . '</b></div>
                        </td>
                </tr>';
            }
            echo '</table>';
        }
        ?>
    </div>
</main>
</div>
    <script>

    </script>
<?php
include 'footer.html';
?>