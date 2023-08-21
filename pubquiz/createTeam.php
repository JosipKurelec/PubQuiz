<?php
$pageTitle = "Settings";
include "head.php";
$succUpdate=0;
date_default_timezone_set('Europe/Zagreb');
include 'connect.php';

$elo=1000;

    $query = "SELECT NameOfTeam FROM teams WHERE Id = ?";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt,'i',$_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $TeamName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

$TeamNameValue = isset($TeamName) ? $TeamName : '';

if(isset($_POST['reg'])){
    $TeamName = $_POST['teamName'];
    if($_SESSION['team'] == 0){
        $query = "SELECT count(NameOfTeam) FROM teams WHERE NameOfTeam = ?";
        $stmt3 = mysqli_stmt_init($db);
        if(mysqli_stmt_prepare($stmt3, $query)){
            mysqli_stmt_bind_param($stmt3,'s',$TeamName);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_store_result($stmt3);
            mysqli_stmt_bind_result($stmt3, $nameCount);
            mysqli_stmt_fetch($stmt3);
            mysqli_stmt_free_result($stmt3);
        }

        if($nameCount == 0){
        $query = "INSERT INTO teams (NameofTeam,Elo) VALUES (?,?)";
        $stmt2=mysqli_stmt_init($db);
        if(mysqli_stmt_prepare($stmt2,$query)){
            mysqli_stmt_bind_param($stmt2,'si',$TeamName,$elo);
            mysqli_stmt_execute($stmt2);
        }

        $query = "SELECT id FROM teams WHERE NameOfTeam = ?";
        $stmt3 = mysqli_stmt_init($db);
        if(mysqli_stmt_prepare($stmt3, $query)){
            mysqli_stmt_bind_param($stmt3,'s',$TeamName);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_store_result($stmt3);
            mysqli_stmt_bind_result($stmt3, $id);
            mysqli_stmt_fetch($stmt3);
            mysqli_stmt_free_result($stmt3);
        }
        
        $query = "UPDATE user SET OwnerOf = ? where username = ?";
        $stmt4=mysqli_stmt_init($db);
        if(mysqli_stmt_prepare($stmt4,$query)){
            mysqli_stmt_bind_param($stmt4,'is', $id, $_SESSION['username']);
            mysqli_stmt_execute($stmt4);
        }
        $_SESSION['id'] = $id;
        $_SESSION['team'] = $TeamName;
        header("Location: uindex.php");
        }
    }else if($_SESSION['team'] != 0){
        $query = "UPDATE teams SET NameOfTeam = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
          mysqli_stmt_bind_param($stmt, 'si', $TeamName, $_SESSION['id']);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          header("Location: uindex.php");
          $_SESSION['team'] = $TeamName;
        }
    }
}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="">
    <h3 class="col-12 txtCenter">Team name</h3><br/>
      <div class="mb-3">
        <input type="text" class="form-control" id="teamName" name="teamName" required value="<?php echo $TeamNameValue; ?>">
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
            }
          }, false);
        });
      })();
    </script>
  </div>
</main>
</div>
<?php
include 'footer.html';
?>