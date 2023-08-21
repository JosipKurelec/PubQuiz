<?php
$pageTitle = "Login";
include 'head.php';
include 'connect.php';
$admin =false;
if(isset($_POST['login'])){
    
    $email=$_POST['email'];
    $password=$_POST['password'];
    $sql = "SELECT email,Password,Level, OwnerOf, username FROM user WHERE email = ?";
    $stmt=mysqli_stmt_init($db);
    if(mysqli_stmt_prepare($stmt,$sql)){
        mysqli_stmt_bind_param($stmt,'s',$email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }    
    mysqli_stmt_bind_result($stmt,$email,$hash,$level, $ownerOf, $username);
    mysqli_stmt_fetch($stmt);

    $_SESSION['username']=$username;

    if(password_verify($password,$hash)){
        if($level==0){
            $sql = "SELECT NameOfTeam FROM teams WHERE id = ?";
            $stmt=mysqli_stmt_init($db);
            if(mysqli_stmt_prepare($stmt,$sql)){
                mysqli_stmt_bind_param($stmt,'i',$ownerOf);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt,$team);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt); 
                $_SESSION['team']=$team;

            } 
        }else if($level==1){
            $sql = "SELECT NameOfQuiz, Color, Picture FROM quiz WHERE id = ?";
            $stmt=mysqli_stmt_init($db);
            if(mysqli_stmt_prepare($stmt,$sql)){
                mysqli_stmt_bind_param($stmt,'i',$ownerOf);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$username, $color, $picture);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt); 

                $_SESSION['color']=$color;
                $_SESSION['picture']=$picture;
            } 
        }
        $_SESSION['username']=$username;
        $_SESSION['level']=$level;
        $_SESSION['id']=$ownerOf;

        if($level==0){

            sleep(2);
            header('Location:uindex.php');
            exit();
        }else if($level==1){
            sleep(2);
            header('Location:qindex.php');
            exit();
        }

    }else{
        echo'Invalid Login';
    }
};
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
      <h1 class="col-12 txtCenter mb-3">Login</h1><br/>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label><br/>
        <input type="text" class="form-control" id="email" name="email" required><br/>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label><br/>
        <input type="password" class="form-control" id="password" name="password" required><br/>
      </div>
      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary" id="login" name="login">Log in</button><br/>
      </div>
      <div class="d-flex justify-content-center">
        <a href="uregister.php">Registration</a>
      </div>
    </form>
    <script type="text/javascript">
    (() => {
        'use strict';

        var forms = document.querySelectorAll('.needs-validation');

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
<?php
mysqli_close($db);
?>