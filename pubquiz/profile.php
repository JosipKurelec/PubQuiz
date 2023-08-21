<?php
$pageTitle = "Profile";
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
$NoTeamsArray = array();

$query = "SELECT t.NameofTeam, t.Elo, u.Elo FROM user u INNER JOIN teams t on u.OwnerOf = t.id where username = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $TeamName, $TeamElo, $UserElo);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT eq.id, eq.NameOfEdition, eq.QuizId, eq.TimeOfQuiz, eq.DateOfQuiz, eq.ThemeOfQuiz, eq.Location, eq.Elo, eq.RegFee, eq.Awards, eq.MaxPlayer, eq.MaxTeams, eq.QuestionShare, eq.nQuestions, (SELECT COUNT(teamID) FROM teamonquiz where quizid = eq.id) AS NoTeams, q.Picture, q.Color FROM editionofquiz eq INNER JOIN quiz q on eq.quizId = q.id INNER JOIN teamonquiz toq ON eq.id = toq.quizid INNER JOIN teamplayers tp ON toq.id = tp.id WHERE (tp.one = ? OR tp.two = ? OR tp.three = ? OR tp.four = ? OR tp.five = ?) AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', eq.TimeOfQuiz), '%Y-%m-%d %H:%i:%s') < NOW() ORDER BY  ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(DateOfQuiz, ' ', eq.TimeOfQuiz), '%Y-%m-%d %H:%i:%s')));";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'sssss', $_SESSION['username'], $_SESSION['username'], $_SESSION['username'], $_SESSION['username'], $_SESSION['username'],);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfEdition, $QuizId, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare, $nQuestions, $NoTeams, $Picture, $Color);
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
        $PictureArray[] = $Picture;
        $ColorArray[] = $Color;
    }
    mysqli_stmt_close($stmt);
}
?>
<style>
</style>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%; background-color: <?php echo $Color; ?>;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row lagana" style="padding: 0%; border-radius: 5% 5% 0% 0%;">
    <?php echo '<div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><h4 class="nameofquiz">' . $_SESSION['username'] . '</h4><b>Elo: </b><p>' . $UserElo . '</p><b>Team: </b><p>' . $TeamName . '</p><b>Team Elo: </b><p>' . $TeamElo . '</p></div>'; ?>
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <h4 class="nameofquiz">Visited quizes: <?php echo count($idArray);?></h4>
        <table>
            <tr>
                <th>Quizes</th>
            </tr>
            <?php if(count($idArray)>0){for ($i = 0; $i < count($idArray); $i++) {
                $rew = explode('|', $AwardsArray[$i]);
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <a href="inspectPastQuiz.php?id=' . $idArray[$i] . '" class="normlink"><div class="pd5 br5 row" style="background-color: ' . hexToRgba($Color) . '"><img class="ppp" src="' . $PictureArray[$i] . '"><h4 class="nameofquiz">' . $NameOfEditionArray[$i] . '</h4><div class="col-sm-3 col-12"><b>Category</b><p>' . $ThemeOfQuizArray[$i] . '</p><b>Location</b><p>' . $LocationArray[$i] . '</p><b>Time of quiz</b><p>' . $DateOfQuizArray[$i] . '</br>' . $TimeOfQuizArray[$i] . '</p></div><div class="col-sm-3 col-12"><b>Elo</b><p>' . $EloArray[$i] . '</p><b>Awards</b><p>1st: ' . $rew[0] . '</p><p>2nd: ' . $rew[1] . '</p><p>3rd: ' . $rew[2] . '</p><b>Registration fee</b><p>' . $RegFeeArray[$i] . '€</p></div><b>' . $NoTeamsArray[$i] . '/' . $MaxTeamsArray[$i] . 'teams</b></div></a>
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