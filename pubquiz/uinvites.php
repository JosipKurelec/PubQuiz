<?php
$pageTitle = "Invites";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "nav.php";
include "hexToRgba.php";

$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $themes[] = $row['Name'];
    }
}

$idArray = array();
$NameOfEditionArray = array();
$QuizIdArray = array();
$TimeOfQuizArray = array();
$DateOfQuizArray = array();
$ThemeOfQuizArray = array();
$LocationArray = array();
$EloArray = array();
$RegFeeArray = array();
$AwardsArray = array();
$MaxPlayerArray = array();
$MaxTeamsArray = array();
$QuestionShareArray = array();
$nQuestionsArray = array();
$ridArray = array();
$teamidArray = array();
$fromidArray = array();
$reditionidArray = array();
$idArrayp = array();
$NameOfEditionArrayp = array();
$QuizIdArrayp = array();
$TimeOfQuizArrayp = array();
$DateOfQuizArrayp = array();
$ThemeOfQuizArrayp = array();
$LocationArrayp = array();
$EloArrayp = array();
$RegFeeArrayp = array();
$AwardsArrayp = array();
$MaxPlayerArrayp = array();
$MaxTeamsArrayp = array();
$QuestionShareArrayp = array();
$nQuestionsArrayp = array();


$query = "SELECT t.quizId FROM teamonquiz t inner join editionofquiz e on t.quizid = e.id where teamId = ? and STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW()";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray[] = $id;
    }
    mysqli_stmt_close($stmt);
}

if($id)
$query = "SELECT NameOfEdition, TimeOfQuiz, DateOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare, nQuestions FROM editionofquiz WHERE id = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() ORDER BY  ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s')));";
$stmt = mysqli_stmt_init($db);
for ($i = 0; $i < count($idArray); $i++) {
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $idArray[$i]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $NameOfEdition, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions);
        while (mysqli_stmt_fetch($stmt)) {
            $NameOfEditionArray[] = $NameOfEdition;
            $TimeOfQuizArray[] = $TimeOfQuiz;
            $DateOfQuizArray[] = $DateOfQuiz;
            $ThemeOfQuizArray[] = $themes[$ThemeOfQuiz];
            $LocationArray[] = $Location;
            $EloArray[] = $Elo;
            $RegFeeArray[] = $RegFee;
            $AwardsArray[] = $Awards;
            $MaxPlayerArray[] = $MaxPlayer;
            $MaxTeamsArray[] = $MaxTeams;
            $QuestionShareArray[] = $QuestionShare;
            $nQuestionsArray[] = $nQuestions;
        }
        mysqli_stmt_close($stmt);
    }
}

$query = "SELECT j.id, fromid,NameofTeam, EditionId FROM joinrequests j INNER JOIN teams t on j.fromid = t.id where ToId = ? and replyresult = 0;";
$stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $rid, $teamid, $fromid, $reditionid);
        while (mysqli_stmt_fetch($stmt)) {
            $ridArray[] = $rid;
            $teamidArray[] = $teamid;
            $fromidArray[] = $fromid;
            $reditionidArray[] = $reditionid;
        }
        mysqli_stmt_close($stmt);
}

$query = "SELECT NameOfEdition, TimeOfQuiz, DateOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare, nQuestions FROM editionofquiz WHERE id = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() ORDER BY  ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s')));";
$stmt = mysqli_stmt_init($db);
for ($i = 0; $i < count($ridArray); $i++) {
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $reditionidArray[$i]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $NameOfEdition, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions);
        while (mysqli_stmt_fetch($stmt)) {
            $NameOfEditionArrayp[] = $NameOfEdition;
            $TimeOfQuizArrayp[] = $TimeOfQuiz;
            $DateOfQuizArrayp[] = $DateOfQuiz;
            $ThemeOfQuizArrayp[] = $themes[$ThemeOfQuiz];
            $LocationArrayp[] = $Location;
            $EloArrayp[] = $Elo;
            $RegFeeArrayp[] = $RegFee;
            $AwardsArrayp[] = $Awards;
            $MaxPlayerArrayp[] = $MaxPlayer;
            $MaxTeamsArrayp[] = $MaxTeams;
            $QuestionShareArrayp[] = $QuestionShare;
            $nQuestionsArrayp[] = $nQuestions;
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $index = $_POST['i'];
        $action = $_POST['action'];
        if ($action === 'accept') {
            $query = "SELECT two, three, four, five from teamplayers where id = (SELECT id FROM teamonquiz WHERE quizid = ? AND teamid = ? LIMIT 1);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'ii', $reditionidArray[$index], $teamidArray[$index]);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $two, $three, $four, $five);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
            }

            echo $two;
            if($two == NULL){
                $query = "UPDATE teamplayers SET two = ? WHERE id = (SELECT id FROM teamonquiz WHERE quizid = ? AND teamid = ? LIMIT 1);";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $reditionidArray[$index], $teamidArray[$index]);
                    mysqli_stmt_execute($stmt);
                }
            }else if($three == NULL){
                $query = "UPDATE teamplayers SET three = ? WHERE id = (SELECT id FROM teamonquiz WHERE quizid = ? AND teamid = ? LIMIT 1);";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $reditionidArray[$index], $teamidArray[$index]);
                    mysqli_stmt_execute($stmt);
                }
            }else if($four == NULL){
                $query = "UPDATE teamplayers SET four = ? WHERE id = (SELECT id FROM teamonquiz WHERE quizid = ? AND teamid = ? LIMIT 1);";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $reditionidArray[$index], $teamidArray[$index]);
                    mysqli_stmt_execute($stmt);
                }
            }else if($five == NULL){
                $query = "UPDATE teamplayers SET five = ? WHERE id = (SELECT id FROM teamonquiz WHERE quizid = ? AND teamid = ? LIMIT 1);";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, 'sii', $_SESSION['username'], $reditionidArray[$index], $teamidArray[$index]);
                    mysqli_stmt_execute($stmt);
                }
            }

            $query = "UPDATE joinrequests set replyresult = 1 where id = ?;";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'i', $ridArray[$index]);
                mysqli_stmt_execute($stmt);
            }
        } elseif ($action === 'decline') {
            $query = "UPDATE joinrequests set replyresult = 2 where id = ?;";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'i', $ridArray[$index]);
                mysqli_stmt_execute($stmt);
            }
        }
        header("Location: uindex.php");
        exit();
    }
}

?>
<style>
    body {
        background-color: <?php echo $Color; ?>;
    }
</style>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <h4 class="nameofquiz">Invite players to your team</h4>
        <table>
            <tr>
                <th>Quizes</th>
            </tr>
            <?php if(count($idArray)>0){for ($i = 0; $i < count($idArray); $i++) {
                $rew = explode('|', $AwardsArray[$i]);
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <a href="inviteplayers.php?id=' . $idArray[$i] . '" class="normlink"><div class="pd5 br5 row"><h4 class="nameofquiz">' . $NameOfEditionArray[$i] . '</h4><div class="col-sm-3 col-12"><b>Category</b><p>' . $ThemeOfQuizArray[$i] . '</p><b>Location</b><p>' . $LocationArray[$i] . '</p><b>Time of quiz</b><p>' . $DateOfQuizArray[$i] . '</br>' . $TimeOfQuizArray[$i] . '</p></div></a>
                    </td>
                </tr>';
                }
             }else{
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <h4>No quizes found</h4>
                    </td>
                </tr>';
             } ?>
        </table>
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <h4 class="nameofquiz">Respond to invites</h4>
        <table>
            <?php if(count($ridArray)>0){for ($i = 0; $i < count($ridArray); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <div class="pd5 br5 row"><b>From: ' . $fromidArray[$i] . '</b><br/><b>To: </b><h4 class="nameofquiz">' . $NameOfEditionArrayp[$i] . '</h4><div class="col-sm-3 col-12"><b>Category</b><p>' . $ThemeOfQuizArrayp[$i] . '</p><b>Location</b><p>' . $LocationArrayp[$i] . '</p><b>Time of quiz</b><p>' . $DateOfQuizArrayp[$i] . '</br>' . $TimeOfQuizArrayp[$i] . '</p></div><form method="post"><input type="hidden" name="i" value="' . $i . '"><button class="tab-btn br5prc" type="submit" name="action" value="accept">Accept</button>    <button class="tab-btn br5prc" type="submit" name="action" value="decline">Decline</button></form></a>
                    </td>
                </tr>';
                }
             }else{
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <h4>No quizes found</h4>
                    </td>
                </tr>';
             } ?>
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