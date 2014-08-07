<h1>Basic Login System</h1>
<p>This is a basic login system created with PHP and MySQL. It is fully functional and provides all the necessary code to create accounts, log in to existing accounts, and create posts when logged in. In this example, all posts are visible regardless of if a user is logged in or not.</p>
<br>
<h2>Your Info</h2>
<?php 
	if (!isset($_SESSION["id"])) echo "<p class='error'>You are not logged in</p><br>";
?>
<h2>Posts</h2>
<?php

?>
<?php 
	// if post is submitted, add the post to the database
	if (isset($_SESSION["id"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST["submit"] == "Post") {
			// scrub input
			$_POST["post_message"] = nl2br(htmlspecialchars($_POST["post_message"]));
	
			// insert record into database
			mysqli_query($link, "INSERT INTO posts(user, in_reply_to, post, date)
								VALUES('".$_SESSION['id']."',
										'0',
										'".$_POST['post_message']."',
										NOW())
			");
		}
	}
	
	// list posts from the database
	$posts = mysqli_query($link, "SELECT posts.post, users.username, posts.date FROM posts INNER JOIN users ON posts.user=users.id");
	
	while ($row = mysqli_fetch_assoc($posts)) {
?>
		<div class='post-message'>
			<p><?php echo $row["post"] ?></p>
			<span class='copyright'><?php echo $row["username"]." - ".$row["date"] ?></span>
		</div>
<?php
	}
?>
<h2>Make a Post</h2>
<?php
	// allow user to make posts if they are signed in
	if (isset($_SESSION["id"])) {
?>
		<form method="post" action="<?php echo "index.php?page=about"?>">
			<textarea name="post_message" rows="8" cols="80" placeholder="Your Post"></textarea>
			<br>
			<input type="submit" name="submit" value="Post">
		</form>
<?php
	} else {
		echo "<p class='error'>You must be logged in to post.</p><br>";
	}
?>


