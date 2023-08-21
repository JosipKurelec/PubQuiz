<?php
$pageTitle = "Edit quiz";
include "head.php";
$succUpdate=0;
date_default_timezone_set('Europe/Zagreb');
include 'connect.php';

$NameOfQuiz = "";
$TimeOfQuiz = "";
$DateOfQuiz = "";
$ThemeOfQuiz = "";
$Location = "";
$RegFee = "";
$Awards = "";
$MaxPlayer = "";
$MaxTeams = "";
$QuestionShare = "";


$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataArray[] = $row;
    }
}

    $query = "SELECT id, NameOfEdition, TimeOfQuiz, DateOfQuiz, ThemeOfQuiz, Location, RegFee, Awards, MaxPlayer, MaxTeams, QuestionShare FROM editionofquiz WHERE QuizId = ?";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt,'s',$_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $TimeOfQuiz, $DateOfQuiz, $ThemeOfQuiz, $Location, $RegFee, $Awards, $MaxPlayer, $MaxTeams, $QuestionShare);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $rew = explode('|', $Awards);
        $rew1 = isset($rew[0]) ? $rew[0] : '';
        $rew2 = isset($rew[1]) ? $rew[1] : '';
        $rew3 = isset($rew[2]) ? $rew[2] : '';
    }

$NameOfQuizValue = isset($NameOfQuiz) ? $NameOfQuiz : '';
$TimeOfQuizValue = isset($TimeOfQuiz) ? $TimeOfQuiz : '';
$DateOfQuizValue = isset($DateOfQuiz) ? $DateOfQuiz : '';
$ThemeOfQuizValue = isset($ThemeOfQuiz) ? $ThemeOfQuiz : '';
$LocationValue = isset($Location) ? $Location : '';
$RegFeeValue = isset($RegFee) ? $RegFee : '';
$rew1Value = isset($rew1) ? $rew1 : '';
$rew2Value = isset($rew2) ? $rew2 : '';
$rew3Value = isset($rew3) ? $rew3 : '';
$MaxPlayerValue = isset($MaxPlayer) ? $MaxPlayer : '';
$MaxTeamsValue = isset($MaxTeams) ? $MaxTeams : '';
$QuestionShareValue = isset($QuestionShare) ? $QuestionShare : '';



if(isset($_POST['reg'])){
    $nameOfQuiz = $_POST['name'];
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
    $awards=$rew1.'|'.$rew2.'|'.$rew3;

    $query = "UPDATE editionofquiz SET NameOfEdition = ?, TimeOfQuiz = ?, DateOfQuiz = ?, ThemeOfQuiz = ?, Location = ?, RegFee = ?, Awards = ?, MaxPlayer = ?, MaxTeams = ?, QuestionShare = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
      mysqli_stmt_bind_param($stmt, 'sssisdssiii', $nameOfQuiz, $timeOfQuiz, $dateOfQuiz, $theme, $location, $regFee, $awards, $teamPlayers, $teams, $qs, $id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    
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
  <div class="row">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="">
      <h1 class="col-12 txtCenter">Edit upcoming quiz</h1><br/>
      <div class="mb-3">
      <label for="name" class="form-label">Name of quiz</label>
        <input type="text" class="form-control" id="name" name="name" required value="<?php echo $NameOfQuizValue; ?>">
      </div>
      <label for="regFee" class="form-label">Registration fee</label>
      <div class="input-group mb-3 noMargin">
        <input type="number" step="any" class="form-control" id="regFee" name="regFee" required value="<?php echo $RegFeeValue; ?>">
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
            echo $row['Name'].' / ';
            echo '<option value="'.$i.'" ';if ($ThemeOfQuizValue == $i) echo 'selected'; echo' >'.$row['Name'].'</option>';
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
        <input type="time" class="form-control" id="timeOfQuiz" name="timeOfQuiz" required  value="<?php echo $TimeOfQuizValue; ?>">
        <div id="timeHelp" class="form-text botMargin">Press on the clock icon to select time.</div>
      </div>
      <div class="mb-3">
        <label for="dateOfQuiz" class="form-label">Date of quiz:</label>
        <input type="date" min="<?php echo $tomorrowDate; ?>" class="form-control" id="dateOfQuiz" name="dateOfQuiz" required value='<?php echo $DateOfQuizValue; ?>'>
        <div id="dateHelp" class="form-text botMargin">Press on the calendar icon to select date.</div>

      </div>
      <label for="teamPlayers" class="form-label">Max players per team</label>
      <div class="input-group mb-3">
        <input type="number" class="form-control" id="teamPlayers" name="teamPlayers" required value="<?php echo $MaxPlayerValue; ?>">
        <span class="input-group-text">players</span>
        <div class="invalid-feedback">Must be greater than or equal to 1</div>
      </div>
      <label for="teams" class="form-label">Max teams per quiz</label>
      <div class="input-group mb-3">
        <input type="number" class="form-control" id="teams" name="teams" required value="<?php echo $MaxTeamsValue; ?>">
        <span class="input-group-text">teams</span>
        <div class="invalid-feedback">Must be greater than or equal to 1</div>
      </div>
      <div class="mb-3">
      <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Street (number),City" required value="<?php echo $LocationValue; ?>">
      </div>
      <div class="mb-3">
        <label for="qs" class="form-label">Question sharing options</label>
        <select class="form-select" id="qs" name="qs" required>
          <option selected disabled value="">Choose...</option>
          <option value="1" <?php if ($QuestionShareValue == 1) echo 'selected'; ?>>Don't share questions</option>
          <option value="2" <?php if ($QuestionShareValue == 2) echo 'selected'; ?>>Share with teams on quiz</option>
          <option value="3" <?php if ($QuestionShareValue == 3) echo 'selected'; ?>>Share with everyone</option>
        </select>
      </div>
      <br/>
      <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary" id="reg" name="reg">Submit</button>
      </div>
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