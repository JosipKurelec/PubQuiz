<?php
$pageTitle = "Team results";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";
include "hexToRgba.php";

$tid = $_GET['tid'];
$eid = $_GET['eid'];

$idArray1 = array();
$questionArray1 = array();
$answerArray1 = array();
$imageArray1 = array();
$eloArray1 = array();
$idArray2 = array();
$questionArray2 = array();
$answerArray2 = array();
$imageArray2 = array();
$eloArray2 = array();
$idArray3 = array();
$questionArray3 = array();
$answerArray3 = array();
$imageArray3 = array();
$eloArray3 = array();

function wElo($Elo1, $Elo2, $result){
    if($Elo1 < 0){
        $k=32;
    }else if($Elo1 > 0){
       $k=16;
    }else{
        $k=24;
    }

    $ea= 1 / (1 + pow(10, (($Elo2 - $Elo1) / 400)));
    $change = intval($k * ($result - $ea));
    return $change;
}

$query = "SELECT id, question, answer, image, elo FROM question where editionid = ? and round = 1 order by id";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $eid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $question, $answer, $image, $elo);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray1[] = $id;
        $questionArray1[] = $question;
        $answerArray1[] = $answer;
        $imageArray1[] = $image;
        $eloArray1[] = $elo;
    }
    mysqli_stmt_close($stmt);
}

$query = "SELECT id, question, answer, image, elo FROM question where editionid = ? and round = 2 order by id";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $eid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $question, $answer, $image, $elo);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray2[] = $id;
        $questionArray2[] = $question;
        $answerArray2[] = $answer;
        $imageArray2[] = $image;
        $eloArray2[] = $elo;
    }
    mysqli_stmt_close($stmt);
}

$query = "SELECT id, question, answer, image, elo FROM question where editionid = ? and round = 3 order by id";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $eid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $question, $answer, $image, $elo);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray3[] = $id;
        $questionArray3[] = $question;
        $answerArray3[] = $answer;
        $imageArray3[] = $image;
        $eloArray3[] = $elo;
    }
    mysqli_stmt_close($stmt);
}

$query = "SELECT NameOfEdition, Elo FROM editionofquiz WHERE Id = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt,'i',$eid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NameOfQuiz, $QuizElo);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT NameOfTeam FROM teams WHERE Id = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt,'i',$tid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NameOfTeam);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if(isset($_POST['submit'])){
    $answers="";
    if(count($idArray1)>0){
    for ($i = 0; $i < count($idArray1); $i++) {
        $answers .= isset($_POST['answer_round1_' . $idArray1[$i]]) ? $_POST['answer_round1_' . $idArray1[$i]] : "";
    }
    }
    if(count($idArray2)>0){
    for ($i = 0; $i < count($idArray2); $i++) {
        $answers .= isset($_POST['answer_round2_' . $idArray2[$i]]) ? $_POST['answer_round2_' . $idArray2[$i]] : "";
    }
    }
    if(count($idArray3)>0){
    for ($i = 0; $i < count($idArray3); $i++) {
        $answers .= isset($_POST['answer_round3_' . $idArray3[$i]]) ? $_POST['answer_round3_' . $idArray3[$i]] : "";
    }
    }
    $query = "UPDATE teamonquiz set answers = ? where teamid = ? and quizid = ?;";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'sii', $answers, $tid, $eid);
        mysqli_stmt_execute($stmt);
    }

    $query = "SELECT one, two, three, four, five FROM teamplayers where id = (SELECT id FROM teamonquiz WHERE teamid = ? and quizid = ?);";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'ii', $tid, $eid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $one, $two, $three, $four, $five);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    $members[] = $one;
    if ($two !== null) {
        $members[] = $two;
    }
    if ($three !== null) {
        $members[] = $three;
    }
    if ($four !== null) {
        $members[] = $four;
    }
    if ($five !== null) {
        $members[] = $five;
    }

    $k=0;
    for ($i = 0; $i < count($members); $i++){
        for ($j = 0; $j < count($idArray1); $j++){
            $resultValue = $answers[$k];
            $query = "INSERT INTO questionmeta (username, questionId, result) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'sii', $members[$i], $idArray1[$j], $resultValue);
                mysqli_stmt_execute($stmt);
            }
            $k++;
        }
        for ($j = 0; $j < count($idArray2); $j++){
            $resultValue = $answers[$k];
            $query = "INSERT INTO questionmeta (username, questionId, result) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'sii', $members[$i], $idArray2[$j], $resultValue);
                mysqli_stmt_execute($stmt);
            }
            $k++;
        }
        for ($j = 0; $j < count($idArray3); $j++){
            $resultValue = $answers[$k];
            $query = "INSERT INTO questionmeta (username, questionId, result) VALUES (?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'sii', $members[$i], $idArray3[$j], $resultValue);
                mysqli_stmt_execute($stmt);
            }
            $k++;
        }
        $k=0;
    }

    $query = "SELECT COUNT(teamId) FROM teamonquiz WHERE answers = '' AND quizId = ?;";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt,'i',$eid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $TeamsLeft);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    if($TeamsLeft == 0){
        $OldQuizElo = $QuizElo;
        $OldTeamElos = array();
        $TeamElos = array();
        $TeamIds = array();
        $TeamAnswers = array();
        $TeamMembers = array();
        $query = "SELECT tp.id, tp.one, (SELECT u1.Elo FROM user u1 WHERE u1.username = tp.one), tp.two, (SELECT u2.Elo FROM user u2 WHERE u2.username = tp.two), tp.three, (SELECT u3.Elo FROM user u3 WHERE u3.username = tp.three), tp.four, (SELECT u4.Elo FROM user u4 WHERE u4.username = tp.four), tp.five, (SELECT u5.Elo FROM user u5 WHERE u5.username = tp.five), answers FROM teamplayers tp INNER JOIN teamonquiz tq ON tp.id = tq.id WHERE tp.id IN (SELECT id FROM teamonquiz WHERE quizid = ?) ORDER BY id;";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'i', $eid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $teamId, $oneplayer, $one, $twoplayer, $two, $threeplayer, $three, $fourplayer, $four, $fiveplayer, $five, $answere);
            while (mysqli_stmt_fetch($stmt)) {
                $teamElo = $one;
                $teammember = $oneplayer;
                if ($two !== null) {
                    $teamElo += wElo($one, $two, 1);
                    $teammember .= '|' . $twoplayer;
                }
                if ($three !== null) {
                    $teamElo += wElo($one, $three, 1);
                    $teammember .= '|' . $threeplayer;
                }
                if ($four !== null) {
                    $teamElo += wElo($one, $four, 1);
                    $teammember .= '|' . $fourplayer;
                }
                if ($five !== null) {
                    $teamElo += wElo($one, $five, 1);
                    $teammember .= '|' . $fiveplayer;
                }
                $TeamElos[] = $teamElo;
                $OldTeamElos [] = $teamElo;
                $TeamIds[] = $teamId;
                $TeamAnswers[] = $answere;
                $TeamMembers[] = $teammember;
            }
            mysqli_stmt_close($stmt);
        }

        if(count($eloArray1)>0){
            for ($i = 0; $i < count($eloArray1); $i++){
                $cntr=0;
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray1[$i] -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }
        if(count($eloArray2)>0){
            for ($i = 0; $i < count($eloArray2); $i++){
                if(count($eloArray1)>0){
                    $cntr = count($eloArray1)-1;
                }else{$cntr = 0;}
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray2[$i] -= wElo($TeamElos[$j],$eloArray2[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray2[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }
        if(count($eloArray3)>0){
            for ($i = 0; $i < count($eloArray3); $i++){
                if(count($eloArray1)>0){
                    $cntr = count($eloArray1)-1;
                }if(count($eloArray2)>0){
                    $cntr += count($eloArray2)-1;
                }else if(count($eloArray1) == 0 && count($eloArray2) == 0){$cntr = 0;}
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray3[$i] -= wElo($TeamElos[$j],$eloArray3[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray3[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }

        $cntr=0;
        if(count($eloArray1)>0){
            for ($i = 0; $i < count($eloArray1); $i++){
                $cntr=0;
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray1[$i] -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $TeamElos[$j] += wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }
        if(count($eloArray2)>0){
            for ($i = 0; $i < count($eloArray2); $i++){
                if(count($eloArray1)>0){
                    $cntr = count($eloArray1)-1;
                }else{$cntr = 0;}
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray2[$i] -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $TeamElos[$j] += wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }
        if(count($eloArray3)>0){
            for ($i = 0; $i < count($eloArray3); $i++){
                if(count($eloArray1)>0){
                    $cntr = count($eloArray1)-1;
                }if(count($eloArray2)>0){
                    $cntr += count($eloArray2)-1;
                }else if(count($eloArray1) == 0 && count($eloArray2) == 0){$cntr = 0;}
                for ($j = 0; $j < count($TeamElos); $j++){
                    $TeamAnswer = $TeamAnswers[$j][$cntr];
                    $eloArray3[$i] -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $QuizElo -= wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $TeamElos[$j] += wElo($TeamElos[$j],$eloArray1[$i], intval($TeamAnswer));
                    $cntr++;
                }
            }
        }

        $cntr=0;
        if(count($eloArray1)>0){
            for ($i = 0; $i < count($eloArray1); $i++){
                $query = "UPDATE question set Elo = ? where id = ?;";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'ii', $eloArray1[$i], $idArray1[$i]);
                    mysqli_stmt_execute($stmt);
                }
            }
        }
        if(count($eloArray2)>0){
            for ($i = 0; $i < count($eloArray2); $i++){
                $query = "UPDATE question set Elo = ? where id = ?;";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'ii', $eloArray2[$i], $idArray2[$i]);
                    mysqli_stmt_execute($stmt);
                }
            }
        }
        if(count($eloArray3)>0){
            for ($i = 0; $i < count($eloArray3); $i++){
                $query = "UPDATE question set Elo = ? where id = ?;";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'ii', $eloArray3[$i], $idArray3[$i]);
                    mysqli_stmt_execute($stmt);
                }
            }
        }


        $query = "UPDATE editionofquiz set elo = ? where id = ?;";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $QuizElo, $eid);
            mysqli_stmt_execute($stmt);
        }

        $EloChange = $QuizElo - $OldQuizElo;
        $query = "UPDATE quiz set elo = elo + ? where id = (SELECT QuizId FROM editionofquiz WHERE id = ?);";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $EloChange, $eid);
            mysqli_stmt_execute($stmt);
        }

        for ($i = 0; $i < count($TeamIds); $i++){
            $query = "UPDATE teamplayers set Elo = ? where id = ?;";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'ii', $TeamElos[$i], $TeamIds[$i]);
                mysqli_stmt_execute($stmt);
            }

            $query = "UPDATE teams SET Elo = Elo + (? - ?) WHERE id = (SELECT teamId FROM teamonquiz WHERE id = ?)";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'iii', $TeamElos[$i], $OldTeamElos[$i], $TeamIds[$i]);
                mysqli_stmt_execute($stmt);
            }

            $TeamPlayers = explode('|', $TeamMembers[$i]);
            for ($j = 0; $j < count($TeamPlayers); $j++){
                $query = "UPDATE user set Elo = Elo + (? - ?) where username = ? AND Level = 0;";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'iis', $TeamElos[$i], $OldTeamElos[$i], $TeamPlayers[$j]);
                    mysqli_stmt_execute($stmt);
                }
            }
        }
    }
    
    header("Location: insertResults.php");
    exit();
}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <h3 class="nameofquiz">Quiz: <?php echo $NameOfQuiz;?></h3><br/>
        <h4 class="nameofquiz">Team: <?php echo $NameOfTeam;?></h4>
    <form method="post" onsubmit="return validateForm()">
    <?php
        echo '<table style="width: 100%;" class="nocur">
        <tr>
            <th>No.</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Correct</th>
        </tr>';
    if(count($idArray1)>0){
        echo '<tr><th colspan="4"><h4 class="nameofquiz">Round 1</h4></th></tr>';
        for ($i = 0; $i < count($idArray1); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">';
                echo '<td>' . ($i+1) . '</td>';
                if($imageArray1[$i] != ""){
                    echo '<td><img src="qImg/' . $imageArray1[$i] . '" alt="" class="pppp"><br/>';
                }else{
                    echo '<td style="padding-top: 1.25rem; padding-bottom: 1.25rem;">';
                }
                echo $questionArray1[$i] . '</td>';
                echo '<td>' . $answerArray1[$i] . '</td>';
                echo '<td><input type="radio" name="answer_round1_' . $idArray1[$i] . '" value="1" style="cursor: pointer;"> Correct ';
                echo '<input type="radio" name="answer_round1_' . $idArray1[$i] . '" value="0" style="cursor: pointer;"> Wrong</td>';
                echo '</tr>';
        }
    }
    if(count($idArray2)>0){
        echo '<tr><th colspan="4"><h4 class="nameofquiz">Round 2</h4></th></tr>';
        for ($i = 0; $i < count($idArray2); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">';
                echo '<td>' . ($i+1) . '</td>';
                if($imageArray2[$i] != ""){
                    echo '<td><img src="qImg/' . $imageArray2[$i] . '" alt="" class="pppp"><br/>';
                }else{
                    echo '<td style="padding-top: 1.25rem; padding-bottom: 1.25rem;">';
                }
                echo $questionArray2[$i] . '</td>';
                echo '<td>' . $answerArray2[$i] . '</td>';
                echo '<td><input type="radio" name="answer_round2_' . $idArray2[$i] . '" value="1"> Correct ';
                echo '<input type="radio" name="answer_round2_' . $idArray2[$i] . '" value="0"> Wrong</td>';
                echo '</tr>';
        }
    }
    if(count($idArray3)>0){
        echo '<tr><th colspan="4"><h4 class="nameofquiz">Round 3</h4></th></tr>';
        for ($i = 0; $i < count($idArray3); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">';
                echo '<td>' . ($i+1) . '</td>';
                if($imageArray3[$i] != ""){
                    echo '<td><img src="qImg/' . $imageArray3[$i] . '" alt="" class="pppp"><br/>';
                }else{
                    echo '<td style="padding-top: 1.25rem; padding-bottom: 1.25rem;">';
                }
                echo $questionArray3[$i] . '</td>';
                echo '<td>' . $answerArray3[$i] . '</td>';
                echo '<td><input type="radio" name="answer_round3_' . $idArray3[$i] . '" value="1"> Correct ';
                echo '<input type="radio" name="answer_round3_' . $idArray3[$i] . '" value="0"> Wrong</td>';
                echo '</tr>';
        }
    }
    ?>
        </table>
        <input type="submit" name="submit" value="Submit">
    </form>
    </div>
  </div>
</main>
</div>
    <script>
        function validateForm() {
            var radioButtons = document.querySelectorAll('input[type="radio"]');
            var radio = true;

            for (var i = 0; i < radioButtons.length; i += 2) {
                if (!radioButtons[i].checked && !radioButtons[i + 1].checked) {
                    radio = false;
                    break;
                }
            }
            if (!radio) {
                alert('Insert all the results!');
                return false;
            }
            return true;
        }
    </script>
<?php
include 'footer.html';
?>