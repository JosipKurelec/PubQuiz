<?php
$pageTitle = "Home";
include "head.php";
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "nav.php";
include "hexToRgba.php";

$listId = array();
$nameOfQuizArray = array();
$themeOfQuizArray = array();
$locationArray = array();
$eloArray = array();
$regFeeArray = array();
$pictureArray = array();
$colorArray = array();

$query = "SELECT Name FROM theme;";
$result = mysqli_query($db, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $themes[] = $row['Name'];
    }
}




if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET['q'])){
        $quizName=$_GET['q'];
        $query = "SELECT q.id, q.NameOfQuiz, q.ThemeOfQuiz, q.Location, q.Elo, q.RegFee, q.Picture, q.Color FROM quiz q INNER JOIN editionofquiz e ON q.id = e.QuizId INNER JOIN teamonquiz t ON e.id = t.quizid WHERE LOWER(NameOfQuiz) LIKE ? GROUP BY q.id ORDER BY COUNT(t.teamid) DESC LIMIT 15;";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            $name = '%' . strtolower($quizName) . '%';
            mysqli_stmt_bind_param($stmt, 's', $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color, );
            while (mysqli_stmt_fetch($stmt)) {
                $listId[] = $id;
                $nameOfQuizArray[] = $NameOfQuiz;
                $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                $locationArray[] = $Location;
                $eloArray[] = $Elo;
                $regFeeArray[] = $RegFee;
                $pictureArray[] = $Picture;
                $colorArray[] = $Color;
            }
            mysqli_stmt_close($stmt);
        }
    }else{
        $query = "SELECT q.id, q.NameOfQuiz, q.ThemeOfQuiz, q.Location, q.Elo, q.RegFee, q.Picture, q.Color FROM quiz q INNER JOIN editionofquiz e ON q.id = e.QuizId  GROUP BY q.id ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(e.DateOfQuiz, ' ', e.TimeOfQuiz), '%Y-%m-%d %H:%i:%s'))) LIMIT 15;";
        $stmt = mysqli_stmt_init($db);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color);
            while (mysqli_stmt_fetch($stmt)) {
                $listId[] = $id;
                $nameOfQuizArray[] = $NameOfQuiz;
                $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                $locationArray[] = $Location;
                $eloArray[] = $Elo;
                $regFeeArray[] = $RegFee;
                $pictureArray[] = $Picture;
                $colorArray[] = $Color;
            }
            mysqli_stmt_close($stmt);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['filter'])) {
        $selectedFilter = $_POST['filter'];
        if($selectedFilter == 'upcoming'){
            $query = "SELECT q.id, q.NameOfQuiz, q.ThemeOfQuiz, q.Location, q.Elo, q.RegFee, q.Picture, q.Color FROM quiz q INNER JOIN editionofquiz e ON q.id = e.QuizId WHERE STR_TO_DATE(CONCAT(e.DateOfQuiz, ' ', e.TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), STR_TO_DATE(CONCAT(e.DateOfQuiz, ' ', e.TimeOfQuiz), '%Y-%m-%d %H:%i:%s'))) LIMIT 15;";
            $stmt = mysqli_stmt_init($db);
            if (mysqli_stmt_prepare($stmt, $query)) {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color);
                while (mysqli_stmt_fetch($stmt)) {
                    $listId[] = $id;
                    $nameOfQuizArray[] = $NameOfQuiz;
                    $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                    $locationArray[] = $Location;
                    $eloArray[] = $Elo;
                    $regFeeArray[] = $RegFee;
                    $pictureArray[] = $Picture;
                    $colorArray[] = $Color;
                }
                mysqli_stmt_close($stmt);
            }
        }
        else if($selectedFilter == 'pop'){
                $query = "SELECT q.id, q.NameOfQuiz, q.ThemeOfQuiz, q.Location, q.Elo, q.RegFee, q.Picture, q.Color FROM quiz q INNER JOIN editionofquiz e ON q.id = e.QuizId INNER JOIN teamonquiz t ON e.id = t.quizid GROUP BY q.id ORDER BY COUNT(t.teamid) DESC LIMIT 15;";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color, );
                    while (mysqli_stmt_fetch($stmt)) {
                        $listId[] = $id;
                        $nameOfQuizArray[] = $NameOfQuiz;
                        $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                        $locationArray[] = $Location;
                        $eloArray[] = $Elo;
                        $regFeeArray[] = $RegFee;
                        $pictureArray[] = $Picture;
                        $colorArray[] = $Color;
                    }
                    mysqli_stmt_close($stmt);
                }
        }
        else if($selectedFilter == 'elohigh'){
            $query = "SELECT id, NameOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Picture, Color FROM quiz ORDER BY Elo DESC LIMIT 15";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color);
                    while (mysqli_stmt_fetch($stmt)) {
                        $listId[] = $id;
                        $nameOfQuizArray[] = $NameOfQuiz;
                        $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                        $locationArray[] = $Location;
                        $eloArray[] = $Elo;
                        $regFeeArray[] = $RegFee;
                        $pictureArray[] = $Picture;
                        $colorArray[] = $Color;
                    }
                    mysqli_stmt_close($stmt);
            }
        }
        else if($selectedFilter == 'elolow'){
            $query = "SELECT id, NameOfQuiz, ThemeOfQuiz, Location, Elo, RegFee, Picture, Color FROM quiz ORDER BY Elo ASC LIMIT 15";
                $stmt = mysqli_stmt_init($db);
                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $ThemeOfQuiz, $Location, $Elo, $RegFee, $Picture, $Color);
                    while (mysqli_stmt_fetch($stmt)) {
                        $listId[] = $id;
                        $nameOfQuizArray[] = $NameOfQuiz;
                        $themeOfQuizArray[] = $themes[$ThemeOfQuiz];
                        $locationArray[] = $Location;
                        $eloArray[] = $Elo;
                        $regFeeArray[] = $RegFee;
                        $pictureArray[] = $Picture;
                        $colorArray[] = $Color;
                    }
                    mysqli_stmt_close($stmt);
                }
        }
    }
}

?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12 offset-lg-0 offset-0 setBox row">
        <form method="get">
            <label for="search">Search:</label>
            <input type="text" id="search" name="q" placeholder="Search name of quiz">
            <input type="submit" value="Search">
        </form>
        <form method="post">
            <label>Filter By:</label>
            <label><input type="radio" name="filter" value="upcoming">Upcoming</label>
            <label><input type="radio" name="filter" value="pop">Popular</label>
            <label><input type="radio" name="filter" value="elohigh">Highest Elo</label>
            <label><input type="radio" name="filter" value="elolow">Lowest Elo</label>
            <input type="submit" value="Apply Filter">
        </form>
        <table>
            <tr>
                <th>Quizes</th>
            </tr>
            <?php if(count($listId)>0){for ($i = 0; $i < count($listId); $i++) {
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <a href="quizProfile.php?quizid='. $listId[$i] .'" class="normlink"><div class="pd5 br5" style="background-color: ' . hexToRgba($colorArray[$i]) . '"><img class="ppp" src="' . $pictureArray[$i] . '"><h4 class="nameofquiz">' . $nameOfQuizArray[$i] . '</h4><b>Category</b><p>' . $themeOfQuizArray[$i] . '</p><b>Location</b><p>' . $locationArray[$i] . '</p><b>Elo</b><p>' . $eloArray[$i] . '</p><b>Registration fee</b><p>' . $regFeeArray[$i] . '</p></div></a>
                    </td>
                </tr>';
                }
             }else{
                echo '<tr style="border-bottom: 3px solid #cccccc;">
                    <td>
                        <h4>No quizes found</h4>
                    </td>
                </tr>';
             } ?>
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