<?php
$pageTitle = "Invites";
include "head.php";
$succUpdate=0;
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";

$listId = array();
$listNameOfQuiz = array();
$listTimeOfQuiz = array();
$listDateOfQuiz = array();
$listLocation = array();
$listInvites = array();

$query = "SELECT e.id, NameOfEdition, TimeOfQuiz, DateOfQuiz, Location, Count(i.TeamId) FROM editionofquiz e INNER JOIN invites i on e.id = i.EditionId WHERE QuizId = ? AND STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() GROUP BY e.id, NameOfEdition, TimeOfQuiz, DateOfQuiz, Location  HAVING Count(i.TeamId) > 0 ORDER BY e.id";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt,'i',$_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $TimeOfQuiz, $DateOfQuiz, $Location, $Invites);
    while (mysqli_stmt_fetch($stmt)) {
        $listId[] = $id;
        $listNameOfQuiz[] = $NameOfQuiz;
        $listTimeOfQuiz[] = $TimeOfQuiz;
        $listDateOfQuiz[] = $DateOfQuiz;
        $listLocation[] = $Location;
        $listInvites[] = $Invites;
    }
    mysqli_stmt_close($stmt);
}

?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
        <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row">
            <table>
                <tr>
                    <th>Quizes</th>
                </tr>
                <?php if(!empty($listInvites)){for ($i = 0; $i < count($listId); $i++) {
                    echo '<tr style="border-bottom: 3px solid #cccccc;">
                        <td>
                            <a href="inviteresp.php?edid=' . $listId[$i] . '" class="normlink">' . $listNameOfQuiz[$i] . '<br/>' . $listDateOfQuiz[$i] . '<br/>' . $listTimeOfQuiz[$i] . '<br/>' . $listLocation[$i] . '<br/>Invites: ' . $listInvites[$i] . '</a>
                        </td>
                    </tr>';
                 }}else{echo '<h2>No quiz invites at the moment</h2>';} ?>
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