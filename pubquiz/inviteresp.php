<?php
$pageTitle = "Invites response";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";
include "hexToRgba.php";

$id = $_GET['edid'];

$IdArray = array();
$NameOfTeamArray = array();
$EloArray = array();

$query = "SELECT Picture, Color FROM quiz where id =?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Picture, $Color);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT NameOfEdition, TimeOfQuiz, DateOfQuiz, Location FROM editionofquiz where id =?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NameOfEdition, $TimeOfQuiz, $DateOfQuiz, $Location);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT t.id, t.NameOfTeam, t.Elo FROM invites i INNER JOIN teams t ON i.TeamId = t.id where EditionId =?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Tid, $NameOfTeam, $Elo);
    while (mysqli_stmt_fetch($stmt)) {
        $IdArray[] = $Tid;
        $NameOfTeamArray[] = $NameOfTeam;
        $EloArray[] = $Elo;
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $approvedTeams = array();
    $rejectedTeams = array();
    if (isset($_POST['action']) && is_array($_POST['action'])) {
        foreach ($_POST['action'] as $teamId => $action) {
            if ($action === 'approve') {
                $approvedTeams[] = $teamId;
            } elseif ($action === 'reject') {
                $rejectedTeams[] = $teamId;
            }
        }
    }
    foreach ($approvedTeams as $teamId) {
        $query = "INSERT INTO teamonquiz (quizId, teamId) VALUES (?, ?)";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $id, $teamId);
            mysqli_stmt_execute($stmt);
        }
        $query = "INSERT INTO teamplayers (id, one) SELECT tq.id, u.username FROM teamonquiz tq INNER JOIN user u ON tq.teamid = u.OwnerOf WHERE tq.quizid = ? AND tq.teamid = ? AND u.level = 0;";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $id, $teamId);
            mysqli_stmt_execute($stmt);
        }
        
        $query = "DELETE FROM invites WHERE TeamId = ? AND EditionId = ?";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $teamId, $id);
            mysqli_stmt_execute($stmt);
        }
    }
    
    foreach ($rejectedTeams as $teamId) {
        $query = "DELETE FROM invites WHERE TeamId = ? AND EditionId = ?";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, 'ii', $teamId, $id);
            mysqli_stmt_execute($stmt);
        }
    }
    header("Location: qinvites.php");
    exit();
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
    <?php echo '<div class="pd5 br5" style="background-color: ' . hexToRgba($Color) . ';  border-radius: 5% 5% 0% 0%;"><img class="ppp" src="' . $Picture . '"><h4 class="nameofquiz">' . $NameOfEdition . '</h4><b>Date of quiz</b><p>' . $DateOfQuiz . '</h4><br/><b>Time of quiz</b><p>' . $TimeOfQuiz . '</p><b>Location</b><p>' . $Location . '</p></div>'; ?>
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <table>
            <tr>
                <h3>Teams Needing Approval:</h3>
            </tr>
            <form method="post">
            <?php
            for ($i = 0; $i < count($IdArray); $i++) {
                echo '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">';
                
                echo '<label>';
                echo $NameOfTeamArray[$i];
                echo '</label>';
                
                echo '<div>';
                echo '<label>';
                echo '<input type="radio" name="action[' . $IdArray[$i] . ']" value="approve"> Approve';
                echo '</label>';
                
                echo '<label style="margin-left: 10px;">';
                echo '<input type="radio" name="action[' . $IdArray[$i] . ']" value="reject"> Reject';
                echo '</label>';
                echo '</div>';
                
                echo '</div>';
            }
            ?>
                <button type="submit">Submit</button>
            </form>
        </table>
    </div>
</main>
</div>
    <script>

    </script>
<?php
include 'footer.html';
?>