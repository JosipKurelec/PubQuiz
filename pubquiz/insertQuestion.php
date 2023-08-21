<?php
$pageTitle = "Insert Questions";
include "head.php";
$succUpdate=0;
date_default_timezone_set('Europe/Zagreb');
include "connect.php";
include "qnav.php";
include "hexToRgba.php";

$query = "SELECT id, NameOfEdition, TimeOfQuiz, DateOfQuiz, Location, Elo FROM editionofquiz e WHERE QuizId = ? AND (STR_TO_DATE(CONCAT(DateOfQuiz, ' ', TimeOfQuiz), '%Y-%m-%d %H:%i:%s') > NOW() OR (SELECT COUNT(id) FROM question where EditionId = e.id) = 0) ORDER BY id Limit 1;";
$stmt = mysqli_stmt_init($db);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt,'i',$_SESSION['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $NameOfQuiz, $TimeOfQuiz, $DateOfQuiz, $Location, $Elo);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receivedData = json_decode(file_get_contents('php://input'), true);
    $lists = $receivedData['lists'];
    $pics = $receivedData['pics'];

    for ($i = 0; $i < count($lists); $i++) {
        for ($j = 0; $j < count($lists[$i]); $j++) {
            $itemParts = explode('|', $lists[$i][$j]);
            $round = $i+1;
            if($itemParts[2] != "" || $itemParts[2] != null){
                $imageFileName = $itemParts[2];
                $fileExtension = pathinfo($imageFileName, PATHINFO_EXTENSION);
                $timestamp = time();
                $randomString = bin2hex(random_bytes(8));
                $finalImageName = $timestamp . "_" . $randomString . "." . $fileExtension;
                $imageData = $pics[$i][$j];
                $imageDataDecoded = base64_decode(explode(",", $imageData)[1]);
                $imageFilePath = 'qImg/' . $finalImageName;
                file_put_contents($imageFilePath, $imageDataDecoded);

                $contentTypes = array(
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                );
                if (isset($contentTypes[$fileExtension])) {
                    header('Content-Type: ' . $contentTypes[$fileExtension]);
                } else {
                    header('Content-Type: application/octet-stream');
                }
                $sql = "INSERT INTO question (EditionId, Round, Question, Answer, Image, Elo) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("iisssi", $id, $round, $itemParts[0], $itemParts[1], $finalImageName, $Elo);

                $stmt->execute();
                $stmt->close();
            }else{
                $sql = "INSERT INTO question (EditionId, Round, Question, Answer, Elo) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("iissi", $id, $round, $itemParts[0], $itemParts[1], $Elo);

                $stmt->execute();
                $stmt->close();
            }
        }
    }
    sleep(1);
    header("Location: qindex.php");
}
?>
<div class="d-flex align-items-center justify-content-center qback" style="padding:2% 0%;">
<main class="container-fluid">
  <div class="row">
  <div class="col-lg-8 col-12 offset-lg-2 offset-0 setBox row">
    <div class="col-lg-12 col-12 d-flex br5" style="background-color: <?php echo hexToRgba($_SESSION['color']); ?> ;">
        <div class="pp">
            <img src="<?php echo $_SESSION['picture']; ?>">
        </div>
        <div class="">
            <p><?php echo $NameOfQuiz.'</br>'.$DateOfQuiz.'</br>'.$TimeOfQuiz.'</br>'.$Location; ?></p>
        </div>
    </div>
    <div class="tabs-container setBox" style="display: <?php if($id==null){echo "none";}else{echo "block";} ?>;">
        <div class="tab">
            <button class="tab-btn active br5prc" onclick="openTab(event, 'round1')">Round 1</button>
            <button class="tab-btn br5prc" onclick="openTab(event, 'round2')">Round 2</button>
            <button class="tab-btn br5prc" onclick="openTab(event, 'round3')">Round 3</button>
            <br/>
            <button class="tab-btn br5prc" onclick="openPopup()">Add Question</button>
        </div>
        <div id="showQs" class="tab-content"></div>
        <br/>
        <button class="send-data-btn br5prc" id="sndtphp" onclick="sendDataToPHP()">Submit</button>
    </div>

    <div id="addQuestionPopup" class="popup br5" style="display: none;">
    <span class="close-btn" onclick="hidePopup()">&times;</span>
        <h2>Add Question</h2>
        <form>
            <label for="question">Question:</label>
            <br/>
            <input type="text" id="question" name="question">
            <p id="questionWarning" style="color: red;"></p>
            <label for="answer">Answer:</label>
            <br/>
            <input type="text" id="answer" name="answer">
            <p id="answerWarning" style="color: red;"></p>
            <label for="pPicture">Image:</label>
            <br/>
            <input class="form-control" type="file" id="pPicture" name="pPicture">
            <br/>
            <button type="button" id="addQuestionButton" onclick="saveQuestion()">Save</button>
        </form>
    </div>
    <h2 id="warning" style="display: <?php if($id==null){echo "block";}else{echo "none";} ?>;">No quizes available for adding questions!</h2>
  </div>
  </div>
</main>
</div>
    <script>
        var submitButton = document.getElementById("sndtphp");
        submitButton.disabled = true;
        var lists = [];
        var first = [];
        var second = [];
        var third = [];
        lists.push(first);
        lists.push(second);
        lists.push(third);
        var pics = [];
        var firstp = [];
        var secondp = [];
        var thirdp = [];
        pics.push(firstp);
        pics.push(secondp);
        pics.push(thirdp);
        var clickedElement = null
        var done = 0;

        function sendDataToPHP() {
            var dataToSend = {
                lists: lists,
                pics: pics
            };

            var formData = new FormData();
            formData.append('data', JSON.stringify(dataToSend));
            fetch('insertQuestion.php', {
            method: 'POST',
            body: JSON.stringify(dataToSend),
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                done = 1;
                window.location.href = "qindex.php";;
            } else {
                console.error("Error sending data.");
            }
        });
        }

        function openTab(event, tabName) {
            clickedElement = event.target;
            tabbtn = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tabbtn.length; i++) {
                tabbtn[i].style.backgroundColor = "#e6e6e6";
            }
            clickedElement.style.backgroundColor = "#cccccc";
            activeTab = tabName;
            updateShowQs();
        }

        function openPopup() {
            if(clickedElement != null){
                var popup = document.getElementById("addQuestionPopup");
                popup.style.display = "block";
            }
        }

        function hidePopup() {
        var popup = document.getElementById("addQuestionPopup");
        popup.style.display = "none";
        }

        function saveQuestion() {
            var questionInput = document.getElementById("question");
            var answerInput = document.getElementById("answer");
            var question = questionInput.value.trim();
            var answer = answerInput.value.trim();
            var picture = document.getElementById('pPicture').files[0];
            var imageFileName = picture ? picture.name : "";

            if (question === "") {
                questionInput.style.border = "1px solid red";
                questionWarning.innerHTML = "Please insert a question.";
                return;
            } else {
                questionInput.style.border = "1px solid #ccc";
                questionWarning.innerHTML = "";
            }

            if (answer === "") {
                answerInput.style.border = "1px solid red";
                answerWarning.innerHTML = "Please insert an answer.";
                return;
            } else {
                answerInput.style.border = "1px solid #ccc";
                answerWarning.innerHTML = "";
            }

            if (picture) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var imageDataURL = e.target.result;

                    if (activeTab === "round1") {
                        first.push(question + "|" + answer + "|" + imageFileName);
                        addPicture(imageDataURL);
                    } else if (activeTab === "round2") {
                        second.push(question + "|" + answer + "|" + imageFileName);
                        addPicture(imageDataURL);
                    } else if (activeTab === "round3") {
                        third.push(question + "|" + answer + "|" + imageFileName);
                        addPicture(imageDataURL);
                    }

                    document.getElementById("question").value = "";
                    document.getElementById("answer").value = "";
                    document.getElementById("pPicture").value = "";

                    var popup = document.getElementById("addQuestionPopup");
                    popup.style.display = "none";

                    updateShowQs();
                };

                reader.onerror = function (e) {
                    console.error('File reading error:', e.target.error);
                };

                reader.readAsDataURL(picture);
            } else {
                if (activeTab === "round1") {
                    first.push(question + "|" + answer + "|" + imageFileName);
                    addPicture(first.length, "");
                } else if (activeTab === "round2") {
                    second.push(question + "|" + answer + "|" + imageFileName);
                    addPicture(second.length, "");
                } else if (activeTab === "round3") {
                    third.push(question + "|" + answer + "|" + imageFileName);
                    addPicture(third.length, "");
                }

                document.getElementById("question").value = "";
                document.getElementById("answer").value = "";
                document.getElementById("pPicture").value = "";

                var popup = document.getElementById("addQuestionPopup");
                popup.style.display = "none";

                updateShowQs();
            }
            submitButton.disabled = false;
        }

        window.addEventListener('beforeunload', function (e) {
            if(!done){
                e.preventDefault();
                var confirmationMessage = 'Did you save everything?';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });

        function getFileName() {
            var fileInput = document.getElementById('pPicture');
            var fileName;
            
            if (fileInput.files.length > 0) {
                fileName = fileInput.files[0].name;
            } else {
                fileName = "";
            }
            return fileName;
        }

        function printListToConsole() {
            for (var i = 0; i < lists.length; i++) {
                console.log('List ' + i + ':');
                for (var j = 0; j < lists[i].length; j++) {
                    console.log(lists[i][j]);
                }
            }
        }

        function generateListContent(list) {
            var tableContent = '<table class="question-table">';
            tableContent += '<tr><th>Question</th><th>Answer</th><th></th></tr>';
            for (var i = 0; i < list.length; i++) {
                var questionAnswerPair = list[i].split('|');
                var question = questionAnswerPair[0];
                var answer = questionAnswerPair[1];
                var imageName = questionAnswerPair[2];

                tableContent += '<tr>';
                tableContent += '<td class="question-cell">' + question + '</td>';
                tableContent += '<td class="answer-cell">' + answer + '</td>';
                tableContent += '<td><span class="remove" onclick="removeQuestion(' + i + ')">&times;</span></td>';
                tableContent += '</tr>';
            }
            tableContent += '</table>';
            return tableContent;
        }

        function removeQuestion(i) {
            if (activeTab === "round1") {
                if (i >= 0 && i < lists[0].length) {
                lists[0].splice(i, 1);
                updateShowQs();
            }
            } else if (activeTab === "round2") {
                if (i >= 0 && i < lists[1].length) {
                lists[1].splice(i, 1);
                updateShowQs();
            }
            } else if (activeTab === "round3") {
                if (i >= 0 && i < lists[2].length) {
                lists[2].splice(i, 1);
                updateShowQs();
            }
            }
        }
        
        function updateShowQs() {
            var showQsDiv = document.getElementById('showQs');
            showQsDiv.innerHTML = '';
            var listContent;

            if (activeTab === "round1") {
                listContent = generateListContent(lists[0]);
            } else if (activeTab === "round2") {
                listContent = generateListContent(lists[1]);
            } else if (activeTab === "round3") {
                listContent = generateListContent(lists[2]);
            }
            showQsDiv.innerHTML += listContent;
        }

        function addPicture(pictureURL) {
            if (activeTab === "round1") {
                firstp.push(pictureURL);
            } else if (activeTab === "round2") {
                secondp.push(pictureURL);
            } else if (activeTab === "round3") {
                thirdp.push(pictureURL);
            }
        }
    </script>
<?php
include 'footer.html';
?>