<?php



$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $mysqli = require __DIR__ . "/connect.php";

    $sql = sprintf("SELECT * FROM users
                    WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    $result = $mysqli->query($sql);
    $users = $result->fetch_assoc();

    if($users){
        if(password_verify($_POST["password"],$users["password_hash"])){
            session_start();

            session_regenerate_id();
            
            $_SESSION["users_id"] = $users["id"];
            header("Location: report.php");
            exit;
        }
    }
    $is_invalid = true;

}

session_start();


 
if (isset($_SESSION["users_id"])){
    $mysqli = require __DIR__ . "/connect.php";

    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["users_id"]}";
    $result = $mysqli->query($sql);

    $users = $result->fetch_assoc();

    header("Location: report.php");
}






?>

<!DOCTYPE html>
<html lang="en">
<!--
	
Copyright (c) 2024 by Ravishankar Kushwah (https://codepen.io/coderavii/pen/gOQwPLJ)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

-->
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="styles/login/style.css" />
	<title>Sign In / Sign up</title>


</head>

<body>
	<div class="modal-overlay" id="modalOverlay">
		<div class="modal">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Sign Up Success!</h5>
					<button class="close-btn" id="closeBtn">&times;</button>
				</div>
				<div class="modal-body">
					<p>Congratulations! you can now Sign in.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="forms-container">
			<div class="signin-signup">
				<form class="sign-in-form" method="post" required>
					<h2 class="title">Sign in</h2>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST["email"] ?? "" ) ?>"/>
					</div>
					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" placeholder="Password" />
					</div>
					<input type="submit" value="Sign In" class="btn solid" />

					<?php if ($is_invalid): ?>
					
						<em>email or password does not match</em>

					<?php endif; ?>


				</form>
				<form action="process.php" id="signupForm" class="sign-up-form" method="post" required>
					<h2 class="title">Sign up</h2>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="firstname" placeholder="Firstname" pattern="(?=.*[a-z]).{5,50}"
							title="First name must contain atleast 5 and at most 50 characters" required />
					</div>
					<div class="input-field">
						<i class="fas fa-user"></i>
						<input type="text" name="lastname" placeholder="Lastname" pattern="(?=.*[a-z]).{5,50}"
							title="Last name must contain atleast 5 and at most 50 characters" required />
					</div>
					<div class="input-field">
						<i class="fas fa-envelope"></i>
						<input type="email" name="email" placeholder="Email" required />
					</div>
					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" placeholder="Password"
							pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
							title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
							required />
					</div>
					<div class="input-field">
						<i class="fas fa-lock"></i>
						<input type="password" name="password_confirm" placeholder="Confirm Password"
							pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
							title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
							required />
					</div>
					<input type="hidden" name="fcm" value="fcm">
					<input type="submit" class="btn" value="Sign up" />

				</form>

			</div>

		</div>

		<div class="panels-container">
			<div class="panel left-panel">
				<div class="content">
					<h3>New to our community ?</h3>
					<p>
						Discover the new technology! Join us and take part on protecting our school
						community with C.I.T.E. Department's Shield-Ed+ as a User/Student.
					</p>
					<button class="btn transparent" id="sign-up-btn">
						Sign up
					</button>
					<form action="index.html">
						<button class="btn transparent" id="sign-up-btn">
							Back
						</button>

					</form>

				</div>
				<img src="https://i.ibb.co/6HXL6q1/Privacy-policy-rafiki.png" class="image" alt="" />
			</div>
			<div class="panel right-panel">
				<div class="content">
					<h3>One of Our Valued Members</h3>
					<p>
						Thank you for being part of C.I.T.E. Department's Shield-Ed+ user, Your presence can help our
						school to be much more safer and peaceful. You can now login!
					</p>
					<button class="btn transparent" id="sign-in-btn">
						Sign in
					</button>
					<form action="index.html">
						<button class="btn transparent" id="sign-up-btn">
							Back
						</button>

					</form>

				</div>
				<img src="https://i.ibb.co/nP8H853/Mobile-login-rafiki.png" class="image" alt="" />
			</div>
		</div>
	</div>

	<script src="styles/login/app.js"></script>
	<script>
		modalOverlay.addEventListener('click', (event) => {
  if (event.target === modalOverlay) {
    modalOverlay.style.display = 'none';
  }
});
	</script>
</body>

</html>
