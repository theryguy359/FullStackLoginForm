<DOCTYPE! html>
<html>
    <head>
        <title>Full Stack Login</title>
	<script>
	    function submitClick()
	    {
		if (document.body.style.backgroundColor == "blue")
		{
		    document.body.style.backgroundColor="white";

		}
		else
		{
		    document.body.style.backgroundColor="blue";
		}
	    }
	    function startForm()
	    {
		var theSubmit = document.getElementById("theSubmit");
	        theSubmit.addEventListener("click", submitClick);
	    }
	    window.addEventListener("load", startForm);        
	</script>
        <style>
            #heading {text-align: center;}
  	    div {margin-left: 15%;}
	</style>
    </head>
    <body>
        <h1 id="heading">Ryan Catterson's Full Stack Login Form</h1>

	<?php
	    $dbuser = "root";
	    $dbpass = /*Password deleted for security */

            $db = mysqli_connect("localhost", $dbuser, $dbpass, "lab2");
	    if (!db) 
	    {
	        print"<p>Could not connect to database</p>";
		print"</body></html>";
		die();
	    }
	    $pageNum = 0;
	    $result = "";

	    /*Login*/
            if( isset($_POST["username"]))
	    {
	    	$username = $_POST["username"];
	    	$pass = $_POST["userpass"];
                $selectQ = "SELECT * FROM user ORDER BY username;";
                $statement = $db->prepare($selectQ);
                $statement->execute();
                $result = $statement->get_result();

	    	if ($username == "Administrator" && $pass == "password")
	    	{
	            $pageNum = 5;
	    	} 
	    	else 
	    	{
		    $result2 = mysqli_query($db, $selectQ);
		    $foo = True;
		    while ($therow = mysqli_fetch_row($result2))
		    {
			if($therow[1] == $username && $therow[2] == $pass)
			{
			    $foo = false;
			}
		    }
		    if ($foo == False)
		    {
		        $pageNum = 4;
		    }
		    else
		    {
			$pageNum = 6;
		    }			
	        }
	    }

	    /*New Account*/
	    if( isset($_POST["newusername"]) )
	    {
           	$newusername = $_POST["newusername"];
            	$newpass = $_POST["newuserpass"];
            	$insertQ = "INSERT INTO user (username, password) VALUES (?, ?)";
            	$stmt = $db->prepare($insertQ);
            	$stmt->bind_param("ss", $newusername, $newpass);
            	$stmt->execute();
	    }

	    if( isset($_POST["page"]) && $pageNum < 1)
	    {
	        $pageNum = $_POST["page"];
		settype($pageNum, "integer");
	    }
	    else if($pageNum < 1)
	    {
		$pageNum = 1;
	    }
	    
	    if($pageNum == 1)
	    {
	    
	?>

        <div>
	    <h2>Login</h2>
	    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	        <label>Your username:
		    <input type="text" name="username">
		</label><br /><br />
		<label>Your password:
		    <input type="password" name="userpass">
	        </label><br /><br />
		<input type="submit" value="Login">
	    </form>
	    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="page" value="2">
		<p><input type="submit" value="Create new account"></p>
	    </form>
	</div>

	<?php } else if ( $pageNum == 2) { ?>

        <div>
            <h2>Create Account</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label>Your username: 
                    <input type="text" name="newusername">
                </label><br /><br />
                <label>Your password:
                    <input type="password" name="newuserpass">
                </label><br /><br />
		<input type="hidden" value="3" name="page">
                <input type="submit" value="Create Account">
            </form>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="page" value="1">
                <p><input type="submit" value="Back to Login"></p>
            </form>
        </div>

	<?php }
        else if ($pageNum == 3)
        { ?>
           <div>
		 <p>You made a new account!</p>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="page" value="1">
                    <p><input type="submit" value="Back to Login"></p>
                </form>
	   </div> <?php
        }
	else if ($pageNum == 4) 
	{ ?>
	    <div>
	        <h2>Welcome <?php echo($_POST["username"]); ?> to my website!</h2>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="page" value="1">
                    <input type="submit" value="Logout">
                </form>
		<form method="post" action="#">
		    <input type="button" id="theSubmit" value="Change Background Color">
		</form>
	    </div>
	  <?php
	}
	else if ($pageNum == 5)
	{
	    ?>
	    <div>
		<h2>Administrator Page</h2>
	    </div>
	    <?php
	    foreach ($result as $resultRow)
	    {
		?>
		<div><p><?php echo($resultRow["userid"] . " " . $resultRow["username"] . " " . $resultRow["password"] . "<br>"); ?> </p></div> 
		<?php
	    }
	    ?>
		<div>
		    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="page" value="1">
			<input type="submit" value="Logout">
		    </form>
		</div>
	    <?php
	}
	else if ($pageNum == 6)
	{
	 ?>
	    <div>
	        <p>Uh oh something went wrong</p>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="page" value="1">
                    <p><input type="submit" value="Back to Login"></p>
                </form>
	    </div>
	<?php
	}
	?>
	<?php mysqli_close($db); ?>
    </body>
</html>
