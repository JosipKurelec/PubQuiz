<?php
$pageTitle = "Quiz profile";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";
include "hexToRgba.php";

$idq = $_SESSION['id'];

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
$NoTeamsArray = array();
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
$NoTeamsArrayp = array();
$TeamStatusArray = array();
$TeamStatus2Array = array();

$query = "SELECT id, NameOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Picture, Color FROM quiz where id =?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $idq);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT id, NameOfEdition, QuizId, TimeOfQuiz, DateOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare, nQuestions , (SELECT COUNT(teamID) FROM teamonquiz where quizid = e.id) AS NoTeams FROM editionofquiz e WHERE QuizId = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() ORDER BY  ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s')));";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $idq);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfEdition, $QuizId, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions, $NoTeams);
    while (mysqli_stmt_fetch($stmt)) {
        $idArray[] = $id;
        $NameOfEditionArray[] = $NameOfEdition;
        $QuizIdArray[] = $QuizId;
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
        $NoTeamsArray[] = $NoTeams;
    }
    mysqli_stmt_close($stmt);
}

$query = "SELECT id, NameOfEdition, QuizId, TimeOfQuiz, DateOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare, nQuestions , (SELECT COUNT(teamID) FROM teamonquiz where quizid = id) AS NoTeams FROM editionofquiz WHERE QuizId = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') < NOW() ORDER BY  ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s')));";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $idq);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfEdition, $QuizId, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions, $NoTeams);
    while (mysqli_stmt_fetch($stmt)) {
        $idArrayp[] = $id;
        $NameOfEditionArrayp[] = $NameOfEdition;
        $QuizIdArrayp[] = $QuizId;
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
        $NoTeamsArrayp[] = $NoTeams;
    }
    mysqli_stmt_close($stmt);
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
    <?php echo '<div class="pd5 br5" style="background-color: ' . hexToRgba($Color) . ';  border-radius: 5% 5% 0% 0%;"><img class="ppp" src="' . $Picture . '"><h4 class="nameofquiz">' . $NameOfQuiz . '</h4><b>Category</b><p>' . $themes[$ThemeOfQuiz] . '</p><b>Location</b><p>' . $Location . '</p><b>Elo</b><p>' . $Elo . '</p><b>Registration fee</b><p>' . $RegFee . '</p></div>'; ?>
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <h4 class="nameofquiz">Upcoming quizes</h4>
        <table>
            <tr>
                <th>Quizes</th>
            </tr>
            <?php if(count($idArray)>0){for ($i = 0; $i < count($idArray); $i++) {
                $rew = explode('|', $AwardsArray[$i]);
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <div class="pd5 br5 row" style="background-color: ' . hexToRgba($Color) . '"><h4 class="nameofquiz">' . $NameOfEditionArray[$i] . '</h4><div class="col-sm-3 col-12"><b>Category</b><p>' . $ThemeOfQuizArray[$i] . '</p><b>Location</b><p>' . $LocationArray[$i] . '</p><b>Time of quiz</b><p>' . $DateOfQuizArray[$i] . '</br>' . $TimeOfQuizArray[$i] . '</p></div><div class="col-sm-3 col-12"><b>Elo</b><p>' . $EloArray[$i] . '</p><b>Awards</b><p>1st: ' . $rew[0] . '</p><p>2nd: ' . $rew[1] . '</p><p>3rd: ' . $rew[2] . '</p><b>Registration fee</b><p>' . $RegFeeArray[$i] . '€</p></div><div class="col-sm-3 col-12 offset-sm-3"><b>' . $NoTeamsArray[$i] . '/' . $MaxTeamsArray[$i] . 'teams</b></div>
                        </div>
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
        <h4 class="nameofquiz">Past quizes</h4>
        <table>
            <tr>
                <th>Quizes</th>
            </tr>
            <?php echo count($idArrayp); if(count($idArrayp)>0){for ($i = 0; $i < count($idArrayp); $i++) {
                $rew = explode('|', $AwardsArrayp[$i]);
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <a href="inspectPastQuiz.php?id=' . $idArrayp[$i] . '" class="normlink"><div class="pd5 br5 row" style="background-color: ' . hexToRgba($Color) . '"><h4 class="nameofquiz">' . $NameOfEditionArrayp[$i] . '</h4><div class="col-sm-3 col-12"><b>Category</b><p>' . $ThemeOfQuizArrayp[$i] . '</p><b>Location</b><p>' . $LocationArrayp[$i] . '</p><b>Time of quiz</b><p>' . $DateOfQuizArrayp[$i] . '</br>' . $TimeOfQuizArrayp[$i] . '</p></div><div class="col-sm-3 col-12"><b>Elo</b><p>' . $EloArrayp[$i] . '</p><b>Awards</b><p>1st: ' . $rew[0] . '</p><p>2nd: ' . $rew[1] . '</p><p>3rd: ' . $rew[2] . '</p><b>Registration fee</b><p>' . $RegFeeArrayp[$i] . '€</p></div><div class="col-sm-3 col-12 offset-sm-3"><b>' . $NoTeamsArrayp[$i] . '/' . $MaxTeamsArrayp[$i] . 'teams</b></div></a>
                        </div>
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
</main>
</div>
    <script>

    </script>
<?php
include 'footer.html';
?>