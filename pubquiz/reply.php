<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="3;url=questions.php">
    <title>Reply</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            <?php echo ($_GET['a'] == 1) ? 'background-color: #4CAF50;' : 'background-color: #FF5733;'; ?>
        }
        .message {
            text-align: center;
            font-size: 24px;
            color: white;
        }
        .icon {
            font-size: 64px;
            margin: 20px;
        }
        .answer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="message">
        <?php
            if ($_GET['a'] == 1) {
                echo '<span class="icon">&#10004;</span>';
                echo '<p>Correct!</p>';
            } else {
                echo '<span class="icon">&#10006;</span>';
                echo '<p>Wrong!</p>';
            }
        ?>
        <div class="answer">
            <p>The correct answer is: <?php echo $_GET['cor']; ?></p>
        </div>
    </div>
</body>
</html>

