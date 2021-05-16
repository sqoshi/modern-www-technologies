<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require "config.php";

    $sql = "INSERT INTO `comments` (username, content, project) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {

        $usr = $_SESSION['username'];
        $cnt = $_POST['content'];
        $pr = $_POST['project'];

        mysqli_stmt_bind_param($stmt, "sss", $usr, $cnt, $pr);
        echo "asd";
        if (mysqli_stmt_execute($stmt)) {
            echo "asd";
            header("location: forum.php");
        } else {
            header("location: projects.php");
            echo "Oops! Statement execution failed. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }


    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="resources/css/forum.css">
    <script src="resources/js/forum.js"></script>
    <title>Welcome</title>
</head>

<body onload='setImage()'>
    <?php
    include('menu.php')
    ?>
    <div id="page-content">
        <div class="tile">
            <div id="image-container">
                <img id='project-preview'></img>
            </div>
            <div id="comments-container">
                <?php
                require "config.php";

                $sql = "SELECT * FROM comments";
                $result = $link->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='comment-row'><div class='comment-username'>" . $row["username"] . "</div><div class='comment-content'>" . $row["content"]  . "</div></div>";
                    }
                }
                $link->close();
                ?>
            </div>
            <form id="input-container" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
                <input type="text" name='project' id='input-project' style="display:none;">
                <input type="text" name='content' id='input-text' value="Comment">
                <input type="submit" id='insert-btn'></input>
            </form>

        </div>
    </div>

</body>

</html>