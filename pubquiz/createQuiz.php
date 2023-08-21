<?php
$pageTitle = "Create Quiz";
include "head.php";
$succUpdate=0;
$lesgo=1;
date_default_timezone_set('Europe/Zagreb');
$tomorrowDate = date('Y-m-d', strtotime('+1 day'));
include 'connect.php';
$query = "SELECT COUNT(*) FROM editionofquiz WHERE QuizId = ?";
$stmt = mysqli_stmt_init($db);

if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $name= $_SESSION['username'].' #'.$count+1;
}

$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataArray[] = $row;
    }
}

$query = "SELECT Elo FROM quiz WHERE id = ?";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt,'s',$_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $elo);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

if(isset($_POST['template'])){
    $lesgo++;
    unset($_POST['template']);
}

if ($lesgo%2 ==0) {
    $query = "SELECT q.RegFee, Awards, TimeOfQuiz, MaxPlayer, MaxTeams, QuestionShare, Location, ThemeOfQuiz, t.Name FROM quiz q inner join Theme t on t.id=ThemeOfQuiz WHERE q.id = ?";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt,'s',$_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $regFee, $awards, $timeOfQuiz, $teamPlayers, $teams, $qs, $location, $tid, $theme);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $rew = explode('|', $awards);
        $rew1 = $rew[0];
        $rew2 = $rew[1];
        $rew3 = $rew[2];
    }
}

$regFeeValue = isset($regFee) ? $regFee : '';
$themeValue = isset($theme) ? $theme : '';
$rew1Value = isset($rew1) ? $rew1 : '';
$rew2Value = isset($rew2) ? $rew2 : '';
$rew3Value = isset($rew3) ? $rew3 : '';
$locationValue = isset($location) ? $location : '';
$timeOfQuizValue = isset($timeOfQuiz) ? $timeOfQuiz : '';
$dateOfQuizValue = isset($dateOfQuiz) ? $dateOfQuiz : '';
$teamPlayersValue = isset($teamPlayers) ? $teamPlayers : '';
$teamsValue = isset($teams) ? $teams : '';
$qsValue = isset($qs) ? $qs : '';


if(isset($_POST['reg'])){
    $regFee = floatval($_POST['regFee']);
    $rew1 = $_POST['rew1'];
    $rew2 = $_POST['rew2'];
    $rew3 = $_POST['rew3'];
    $theme = intval($_POST['theme']);
    $location = $_POST['location'];
    $timeOfQuiz = $_POST['timeOfQuiz'];
    $theme = intval($_POST['theme']);
    $dateOfQuiz = $_POST['dateOfQuiz'];
    $teamPlayers = intval($_POST['teamPlayers']);
    $teams = intval($_POST['teams']);
    $qs= intval($_POST['qs']);
    $finished=1;
    $rew=$rew1.'|'.$rew2.'|'.$rew3;

    $query = "INSERT INTO editionofquiz (NameOfEdition, QuizId, ThemeOfQuiz, TimeOfQuiz, DateOfQuiz, Location, Elo, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare) VALUES (? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt2=mysqli_stmt_init($db);
    if(mysqli_stmt_prepare($stmt2,$query)){
        mysqli_stmt_bind_param($stmt2,'siisssidsiii',$name, $_SESSION['id'], $theme, $timeOfQuiz, $dateOfQuiz, $location, $elo, $regFee, $rew, $teamPlayers, $teams, $qs);
        mysqli_stmt_execute($stmt2);

        $succUpdate=1;
    }
}
if($succUpdate==1) {
    sleep(1);
    header("Location: qindex.php");
}
include "qnav.php";
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row d-flex align-items-center justify-content-center">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="">
      <h1 class="col-12 txtCenter">Create quiz</h1><br/>
      <div class="mb-3">
      <label for="name" class="form-label">Name of quiz</label>
        <input type="text" class="form-control" id="name" name="name" required value="<?php echo $name; ?>">
      </div>
      <label for="regFee" class="form-label">Registration fee</label>
      <div class="input-group mb-3 noMargin">
        <input type="number" step="any" class="form-control" id="regFee" name="regFee" required value="<?php echo $regFee; ?>">
        <span class="input-group-text">€</span>
      </div>
      <div id="regFeeHelp" class="form-text botMargin">Registration fee for the whole team, not player.</div>
      <div class="mb-3">
        <label for="theme" class="form-label">Theme of quiz</label>
        <select class="form-select" id="theme" name="theme" required>
          <option selected disabled value="">Choose...</option>
        <?php 
        $i=1;
        foreach ($dataArray as $row) {
            echo '<option value="'.$i.'" ';if(isset($tid)){if ($tid == $i) echo 'selected';} echo' >'.$row['Name'].'</option>';
            $i++;
        } ?>
        </select>
      </div>
      <label for="rewards" class="form-label">Awards</label>
      <div class="input-group botMargin">
        <input type="text" id="rew1" name="rew1" placeholder="1st" class="form-control" required  value="<?php echo $rew1Value; ?>">
        <input type="text" id="rew2" name="rew2" placeholder="2nd" class="form-control" required  value="<?php echo $rew2Value; ?>">
        <input type="text" id="rew3" name="rew3" placeholder="3rd" class="form-control" required  value="<?php echo $rew3Value; ?>">
      </div>
      <div class="mb-3">
        <label for="timeOfQuiz" class="form-label"></label>Time of quiz:</label>
        <input type="time" class="form-control" id="timeOfQuiz" name="timeOfQuiz" required  value="<?php echo $timeOfQuiz; ?>">
        <div id="timeHelp" class="form-text botMargin">Press on the clock icon to select time.</div>
      </div>
      <div class="mb-3">
        <label for="dateOfQuiz" class="form-label">Date of quiz:</label>
        <input type="date" min="<?php echo $tomorrowDate; ?>" class="form-control" id="dateOfQuiz" name="dateOfQuiz" required>
        <div id="dateHelp" class="form-text botMargin">Press on the calendar icon to select date.</div>

      </div>
      <label for="teamPlayers" class="form-label">Max players per team</label>
      <div class="input-group mb-3">
        <input type="number" class="form-control" id="teamPlayers" name="teamPlayers" required value="<?php echo $teamPlayers; ?>">
        <span class="input-group-text">players</span>
        <div class="invalid-feedback">Must be greater than or equal to 1</div>
      </div>
      <label for="teams" class="form-label">Max teams per quiz</label>
      <div class="input-group mb-3">
        <input type="number" class="form-control" id="teams" name="teams" required value="<?php echo $teams; ?>">
        <span class="input-group-text">teams</span>
        <div class="invalid-feedback">Must be greater than or equal to 1</div>
      </div>
      <div class="mb-3">
      <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Street (number),City" required value="<?php echo $locationValue; ?>">
      </div>
      <div class="mb-3">
        <label for="qs" class="form-label">Question sharing options</label>
        <select class="form-select" id="qs" name="qs" required>
          <option selected disabled value="">Choose...</option>
          <option value="1" <?php if ($qs == 1) echo 'selected'; ?>>Don't share questions</option>
          <option value="2" <?php if ($qs == 2) echo 'selected'; ?>>Share with teams on quiz</option>
          <option value="3" <?php if ($qs == 3) echo 'selected'; ?>>Share with everyone</option>
        </select>
      </div>
      <br/>
      <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary" id="reg" name="reg">Submit</button>
      </div>
    </form>
    <form class="col-lg-3 col-12" novalidate method="POST" action="" style="height:100%">
    <button class="btn btn-secondary col-lg-3 col-12" id="template" name="template" type="submit">Use template</button>
    </form>
    <script type="text/javascript">
    (() => {
        'use strict';

        var forms = document.querySelectorAll('.needs-validation');
        var templateButton = document.querySelector('#template');

        Array.from(forms).forEach((form) => {
          form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
              form.classList.add('was-validated');
            } else {
              var teamPlayersInput = form.querySelector('#teamPlayers');
              var teamsInput = form.querySelector('#teams');
              var teamPlayers = parseInt(teamPlayersInput.value);
              var teams = parseInt(teamsInput.value);
              var dateInput = form.querySelector('#dateOfQuiz');
              var date = dateInput.value;
              var formValidatedInput = form.querySelector('#formValidated');

              if (teamPlayers < 1 || isNaN(teamPlayers)) {
                teamPlayersInput.classList.remove('is-valid');
                teamPlayersInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
              } else {
                teamPlayersInput.classList.remove('is-invalid');
                teamPlayersInput.classList.add('is-valid');
              }

              if (teams < 1 || isNaN(teams)) {
                teamsInput.classList.remove('is-valid');
                teamsInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
              } else {
                teamsInput.classList.remove('is-invalid');
                teamsInput.classList.add('is-valid');
              }
              if (date ==="") {
                dateInput.classList.remove('is-valid');
                dateInput.classList.add('is-invalid');
                event.preventDefault();
                event.stopPropagation();
              } else {
                dateInput.classList.remove('is-invalid');
                dateInput.classList.add('is-valid');
              }
              form.classList.add('was-validated');
            }
          }, false);
        });
        templateButton.addEventListener('click', (event) => {
            var form = event.target.closest('form');
            form.classList.remove('was-validated');
            var inputs = form.querySelectorAll('.form-control');
            inputs.forEach((input) => {
                input.classList.remove('is-valid');
                input.classList.remove('is-invalid');
        });
      });
      })();
    </script>
  </div>
</main>
</div>
<?php
include 'footer.html';
?>