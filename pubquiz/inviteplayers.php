<?php
$pageTitle = "Invites";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "nav.php";
include "hexToRgba.php";

$id = $_GET['id'];
$NameOfPlayerArray = array();
$EloArray = array();

$query = "SELECT NameOfEdition, MaxPlayer from editionofquiz where id = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NameOfQuiz, $MaxPlayer);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT id from teamonquiz where quizId = ? and teamId = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'ii', $id, $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $teamonquizid);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT COUNT(*) FROM teamplayers WHERE (one IS NOT NULL OR two IS NOT NULL OR three IS NOT NULL OR four IS NOT NULL OR five IS NOT NULL) AND id = ?";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $teamonquizid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $CurrPlayer);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$query = "SELECT u.username, u.elo FROM user u WHERE u.level = 0 AND NOT EXISTS (SELECT 1 FROM teamonquiz tq INNER JOIN teamplayers tp on tp.id = tq.id WHERE tq.quizid = ? AND (tp.id = tq.id AND (u.username = tp.one OR u.username = tp.two OR u.username = tp.three OR u.username = tp.four OR u.username = tp.five)));";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Username, $Elo);
    while (mysqli_stmt_fetch($stmt)) {
        $NameOfPlayerArray[] = $Username;
        $EloArray[] = $Elo;
    }
    mysqli_stmt_close($stmt);
}

$query = "SELECT two from teamplayers where id = 1;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dvojka);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['invited']) && is_array($_POST['invited'])) {
        foreach ($_POST['invited'] as $invitedPlayer) {
            $zero = 0;
            $query = "INSERT INTO joinrequests (FromId, ToId, EditionId, ReplyResult) VALUES (?, ?, ?, ?);";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_bind_param($stmt, 'isii', $_SESSION['id'], $invitedPlayer, $id, $zero);
                mysqli_stmt_execute($stmt);
            }
        }
        header("Location: uinvites.php");
        exit();
    }
}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding: 2% 0%; background-color: <?php echo $Color; ?>;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row" style="padding: 0%; border-radius: 5% 5% 0% 0%;">
    </div>
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox br0 row" >
        <table>
            <tr>
                <h3>Select player to invite to <?php echo $NameOfQuiz; ?></h3>
            </tr>
            <form method="post">
            <?php
            for ($i = 0; $i < count($NameOfPlayerArray); $i++) {                
                echo '<tr><label>' . $NameOfPlayerArray[$i] . '<input type="checkbox" name="invited[]" value="' . $NameOfPlayerArray[$i] . '" style="float: right;"><br/>ELO: ' . $EloArray[$i] . '</label><hr/></tr>';
            }
            ?>
                <button type="submit">Submit</button>
            </form>
        </table>
    </div>
</main>
</div>
<script>
    function limitCheckboxSelection() {
        var maxPlayer = <?php echo $MaxPlayer; ?>;
        var curPlayer = <?php echo $CurrPlayer; ?>;
        var checkboxes = document.querySelectorAll('input[name="invited[]"]');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    curPlayer++;
                    console.log('Checked Count:', curPlayer);
                    if (curPlayer > maxPlayer) {
                        this.checked = false;
                        curPlayer--;
                        alert('You can only invite up to ' + maxPlayer + ' players.');
                    }
                } else {
                    curPlayer--;
                }
            });
        });
    }
    document.addEventListener('DOMContentLoaded', limitCheckboxSelection);
</script>
<?php
include 'footer.html';
?>