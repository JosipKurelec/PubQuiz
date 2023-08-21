<?php
$pageTitle = "Registration";
include "head.php";
include 'connect.php';
    $uniqueMail = true;
    $uniqueName = true;
    $registeredUser = false;
    $display='none';
    $query = "SELECT Name FROM theme;";
    $result = mysqli_query($db, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $themes[] = $row['Name'];
        }
    }
    if(isset($_POST['reg'])){
            if(!empty($_POST['email']) && !empty($_POST['quizname']) && !empty($_POST['location']) && !empty($_POST['theme']) && !empty($_POST['pass1']) && !empty($_POST['pass2']) 
            && ($_POST['pass1'] == $_POST['pass2'])){

            $email = $_POST['email'];
            $quizname = $_POST['quizname'];
            $theme = $_POST['theme'];
            $location=$_POST['location'];
            $password = $_POST['pass1'];
            $hashPass = password_hash($password,CRYPT_BLOWFISH);
            $level=1;
            $elo=1000;
            
            $query = "SELECT NameOfQuiz FROM quiz WHERE NameOfQuiz = ?";
            $stmt = mysqli_stmt_init($db);
            if(mysqli_stmt_prepare($stmt, $query)){
                mysqli_stmt_bind_param($stmt,'s',$quizname);
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
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $quizname;
                $_SESSION['level'] = $level;

                $defPicture = 'pPictures/defaultpicture.jpg';
                $_SESSION['picture']=$defPicture;
                $query = "INSERT INTO quiz (NameOfQuiz, ThemeOfQuiz , Location ,Elo, picture) VALUES (? ,?, ?, ?, ?)";
                $stmt2=mysqli_stmt_init($db);
                if(mysqli_stmt_prepare($stmt2,$query)){
                    mysqli_stmt_bind_param($stmt2,'sssis',$quizname, $theme, $location ,$elo, $defPicture);
                    mysqli_stmt_execute($stmt2);
                }

                $query = "SELECT id FROM quiz WHERE NameOfQuiz = ?";
                $stmt3 = mysqli_stmt_init($db);
                if(mysqli_stmt_prepare($stmt3, $query)){
                    mysqli_stmt_bind_param($stmt3,'s',$quizname);
                    mysqli_stmt_execute($stmt3);
                    mysqli_stmt_store_result($stmt3);
                    mysqli_stmt_bind_result($stmt3, $id);
                    mysqli_stmt_fetch($stmt3);
                    mysqli_stmt_free_result($stmt3);
                }

                $_SESSION['id'] = $id;
                $nameuser = 'nah';
                $query = "INSERT INTO user (email,Password,Level,OwnerOf, username) VALUES (?,?,?,?,?)";
                $stmt4=mysqli_stmt_init($db);
                if(mysqli_stmt_prepare($stmt4,$query)){
                    mysqli_stmt_bind_param($stmt4,'ssiis',$email,$hashPass,$level,$id, $nameuser);
                    mysqli_stmt_execute($stmt4);
                    $registeredUser = true;
                }
                header("Location: qsettings.php");
            };
            mysqli_close($db);
        };
    };
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <form class="col-lg-6 col-12 offset-lg-3 offset-0 setBox needs-validation" novalidate method="POST" action="" enctype="multipart/form-data">
      <h1 class="col-12 txtCenter mb-3">Quiz registration</h1><br/>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label><br/>
        <input type="text" class="form-control" id="email" name="email" required><br/>
      </div>
      <div class="mb-3">
        <label for="quizname" class="form-label">Quiz name</label><br/>
        <input type="text" class="form-control" id="quizname" name="quizname" required><br/>
      </div>
      <div class="mb-3">
        <label for="theme" class="form-label">Theme of quiz</label>
        <select class="form-select" id="theme" name="theme" required>
          <option selected disabled value="">Choose...</option>
        <?php 
        $i=1;
        foreach ($themes as $row) {
            echo '<option value="'.$i.'">' . $row . '</option>';
            $i++;
        } ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="location" class="form-label">Location</label><br/>
        <input type="text" class="form-control" id="location" name="location" placeholder="Address, City" required><br/>
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
        <a href="uregister.php">Register a player</a>
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
