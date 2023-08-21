<?php
$pageTitle = "Home";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
if($_SESSION['level'] == 0){
    include "nav.php";
}else if($_SESSION['level'] == 1){
    include "qnav.php";
}

include "hexToRgba.php";

$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $themes[] = $row['Name'];
    }
}

$query = "SELECT NameOfQuiz, Elo FROM quiz ORDER BY Elo DESC LIMIT 20;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NameOfQuiz, $Eloq);
    while (mysqli_stmt_fetch($stmt)) {
        $nameOfQuizArray[] = $NameOfQuiz;
        $eloqArray[] = $Eloq;
    }
    mysqli_stmt_close($stmt);
}

if($_SESSION['level'] == 1){
    $query = "SELECT NameOfQuiz, Elo, (SELECT COUNT(*) + 1 FROM quiz q2 WHERE q2.Elo > q1.Elo) AS row_number FROM quiz q1 WHERE id = ?;";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $NameOfQuizu, $Eloqu, $Positionq);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

$query = "SELECT username, Elo FROM user WHERE level = 0 ORDER BY Elo DESC LIMIT 20;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Username, $Elou);
    while (mysqli_stmt_fetch($stmt)) {
        $usernameArray[] = $Username;
        $elouArray[] = $Elou;
    }
    mysqli_stmt_close($stmt);
}

if($_SESSION['level'] == 0){
    $query = "SELECT username, Elo, (SELECT COUNT(*) + 1 FROM user u2 WHERE u2.level = 0 AND u2.Elo > u1.Elo) FROM user u1 WHERE u1.level = 0 AND u1.username = ? ORDER BY Elo DESC;";
    $stmt = mysqli_stmt_init($db);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['username']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $Usernameu, $Elouu, $Positionu);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row">
        <button id="playersButton" class="btn btn-secondary col-lg-2 col-12">Show Players</button>
        <button id="quizzesButton" class="btn btn-secondary col-lg-2 col-12">Show Quizzes</button>
        <table id="playersTable">
            <tr>
                <th>Players</th>
            </tr>
            <?php 
            $beenu = 0;
            for ($i = 0; $i < count($usernameArray); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;'; if($_SESSION['level'] == 0 && $usernameArray[$i] == $Usernameu){$beenu = 1; echo 'background-color: greenyellow;';} echo '">
                        <td style="width: 3rem"> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $i+1 . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $usernameArray[$i] . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;">' . $elouArray[$i] . '</p></div>
                        </td>
                </tr>';
                }
            if($beenu == 0 && $_SESSION['level'] == 0){
                echo '<tr style="border-bottom: 3px solid #cccccc; background-color: greenyellow;">
                        <td style="width: 3rem"> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $Positionu . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $Usernameu . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;">' . $Elouu . '</p></div>
                        </td>
                </tr>';
            }
            ?>
        </table>
        <table id="quizzesTable" style="display: none;">
            <tr>
                <th>Quizes</th>
            </tr>
            <?php 
            $beenq = 0;
            for ($i = 0; $i < count($nameOfQuizArray); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;'; if($_SESSION['level'] == 1 && $nameOfQuizArray[$i] == $NameOfQuizu){$beenq = 1; echo 'background-color: greenyellow;';} echo '">
                        <td style="width: 3rem"> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $i+1 . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $nameOfQuizArray[$i] . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;">' . $eloqArray[$i] . '</p></div>
                        </td>
                </tr>';
                }
            if($beenq == 0 && $_SESSION['level'] == 1){
                echo '<tr style="border-bottom: 3px solid #cccccc; background-color: greenyellow;">
                        <td style="width: 3rem"> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $Positionq . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><b>' . $NameOfQuizu . '</b></div>
                        </td>
                        <td> 
                            <div class="pd5 br5" style="border-radius: 5% 5% 0% 0%;"><p style="float: right;">' . $Eloqu . '</p></div>
                        </td>
                </tr>';
            }
            ?>
        </table>
    </div>
  </div>
</main>
</div>
<script>
    var playersTable = document.getElementById("playersTable");
    var quizzesTable = document.getElementById("quizzesTable");
    var playersButton = document.getElementById("playersButton");
    var quizzesButton = document.getElementById("quizzesButton");
    
    playersButton.addEventListener("click", () => {
        playersTable.style.display = "table";
        quizzesTable.style.display = "none";
    });
    
    quizzesButton.addEventListener("click", () => {
        playersTable.style.display = "none";
        quizzesTable.style.display = "table";
    });
</script>
<?php
include 'footer.html';
?>