<?php
$pageTitle = "Insert Questions";
include "head.php";
$succUpdate=0;
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";
include "hexToRgba.php";

$tidArray = array();
$teamidArray = array();
$teamnameArray = array();
$idArray = array();
$edArray = array();
$noteamsArray = array();

$query = "SELECT e.id, e.NameOfEdition, (SELECT COUNT(teamId) FROM teamonquiz t WHERE t.quizid = e.id and answers = '') AS NoTeams FROM editionofquiz e WHERE e.QuizId = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') < NOW() AND (SELECT COUNT(teamId) FROM teamonquiz t WHERE t.quizid = e.id and answers = '') > 0 AND (SELECT COUNT(id) FROM question q WHERE q.editionid = e.id) ORDER BY e.id, e.NameOfEdition";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt,'i',$_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $NoTeams);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray[] = $id;
        $edArray[] = $NameOfQuiz;
        $noteamsArray[] = $NoTeams;
    }
    mysqli_stmt_close($stmt);
}

if(count($idArray)>0){
for ($i = 0; $i < count($idArray); $i++){
$query = "SELECT t.id, t.teamId, NameOfTeam FROM teamonquiz t INNER JOIN editionofquiz e on t.quizid = e.id INNER JOIN teams on teams.id = t.teamid where e.id = ? and answers = '';";
$stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $idArray[$i]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $tid, $teamid, $teamname);
        while (mysqli_stmt_fetch($stmt)) {
            $tidArray[] = $tid;
            $teamidArray[] = $teamid;
            $teamnameArray[] = $teamname;
        }
        mysqli_stmt_close($stmt);
}
}
}

?>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <?php $k=0;
        if(count($idArray)>0){for ($i = 0; $i < count($idArray); $i++){
            echo '<h4 class="nameofquiz">Teams on ' . $edArray[$i] . '</h4><table style="padding-bottom: 5%;">';
                for ($j = 0; $j < $noteamsArray[$i]; $j++) {
                    echo '<tr style="border-bottom: 3px solid #cccccc;">
                        <td>
                            <a href="insertTeamResults.php?tid=' . $teamidArray[$k] . '&eid=' . $idArray[$i] . '" class="normlink"><div class="pd5 br5 row"><p class="nameofquiz">' . $teamnameArray[$k] . '</p></div></a>
                        </td>
                    </tr>';
                    $k++;
                }
                    echo '</table>';
                }
                }else{
                    echo '<table><tr style="border-bottom: 3px solid #cccccc;">
                        <td>
                            <h4>No quizes found</h4>
                        </td>
                    </tr>
                    </table>';
                }?>
        </table>
    </div>
  </div>
</main>
</div>
    <script>

    </script>
<?php
include 'footer.html';
?>