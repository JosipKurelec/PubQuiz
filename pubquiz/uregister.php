<?php
$pageTitle = "Registration";
include "head.php";
    $uniqueMail = true;
    $uniqueName = true;
    $registeredUser = false;
    $display='none';
    if(isset($_POST['reg'])){
            if(!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['pass1']) && !empty($_POST['pass2']) 
            && ($_POST['pass1'] == $_POST['pass2'])){
                
            include 'connect.php';

            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['pass1'];
            $hashPass = password_hash($password,CRYPT_BLOWFISH);
            $level=0;
            $elo=1000;
            
            $query = "SELECT username FROM user WHERE username = ?";
            $stmt = mysqli_stmt_init($db);
            if(mysqli_stmt_prepare($stmt, $query)){
                mysqli_stmt_bind_param($stmt,'s',$username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
            }

            $query = "SELECT email FROM user WHERE email = ?";
            $stmt1 = mysqli_stmt_init($db);
            if(mysqli_stmt_prepare($stmt1, $query)){
                mysqli_stmt_bind_param($stmt1,'s',$email);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_store_result($stmt1);
            }

            if(mysqli_stmt_num_rows($stmt1) > 0){
                $uniqueMail = false;
            }
            if(mysqli_stmt_num_rows($stmt) > 0){
                $uniqueName = false;
            }
            if($uniqueMail==true && $uniqueName==true){
                $elo=1000;
                $query = "INSERT INTO user (email, username, Password, Level, Elo) VALUES (?,?,?,?,?)";
                $stmt4=mysqli_stmt_init($db);
                if(mysqli_stmt_prepare($stmt4,$query)){
                    mysqli_stmt_bind_param($stmt4,'sssii',$email, $username, $hashPass,$level, $elo);
                    mysqli_stmt_execute($stmt4);
                    $registeredUser = true;
                }
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['level'] = $level;
                header("Location: createTeam.php");

            };
            mysqli_close($db);
        };
    };
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
      <h1 class="col-12 txtCenter mb-3">Player registration</h1><br/>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label><br/>
        <input type="text" class="form-control" id="email" name="email" required><br/>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label><br/>
        <input type="text" class="form-control" id="username" name="username" required><br/>
      </div>
      <div class="mb-3">
        <label for="password1" class="form-label">Password</label><br/>
        <input type="password" class="form-control" id="pass1" name="pass1" required><br/>
      </div>
      <div class="mb-3">
        <label for="password2" class="form-label">Repeat password</label><br/>
        <input type="password" class="form-control" id="pass2" name="pass2" required><br/>
      </div>
      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary" id="reg" name="reg">Register</button><br/>
      </div>
      <div class="d-flex justify-content-center">
        <a href="qregister.php">Register a quiz</a>
      </div>
    </form>
    <script type="text/javascript">
    (() => {
        'use strict';

        var forms = document.querySelectorAll('.needs-validation');
        var mailFormat = /\S+@\S+\.\S+/;
        

        Array.from(forms).forEach((form) => {
          form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
              form.classList.add('was-validated');
            }else {
            var pass1 = form.querySelector('#pass1');
            var pass2 = form.querySelector('#pass2');
            var email = form.querySelector('#email');
            if(pass1.value != pass2.value){
              event.preventDefault();
              event.stopPropagation();
              pass2.classList.remove('is-valid');
              pass2.classList.add('is-invalid');
              form.classList.add('was-validated');
            }
            if(!email.value.match(mailFormat)){
              event.preventDefault();
              event.stopPropagation();
              email.classList.remove('is-valid');
              email.classList.add('is-invalid');
              form.classList.add('was-validated');
            }
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