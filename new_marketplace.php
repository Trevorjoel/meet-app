<!DOCTYPE html>
<html>
<head>
<style type="text/css" media="screen">	
.bd-example {
	    box-sizing: inherit
    position: relative;
    padding: 1rem;
    margin: 1rem -1rem 1rem 1rem;
    border: solid #f7f7f9;
    border-width: .2rem .2 .2;
    width: 700px;
}
.reduce{
	width: 94%;
}
</style>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NEW FORM</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" 
	integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>



<body>
	





 
 
 <div class="bd-example container"">
 <h1 id="sub"> Advertise your business or product.</h1>
<?php
require_once('connectvars.php');
 $output_error = false;
 $output_error_email = false;
 $output_error_images = false;
 $form_dissapear = false;
if (isset($_POST['submit'])) {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['bus_name'])) {
        $output_error = true;
    }

    if ($output_error) {
        echo "<img class='tst3' src='images/whoops.png'><br><p class=S'danger' style='color:red;'>Please check the mandatory form fields marked with an *, you seem to be missing something.</p>";
    } else {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        filter_input(INPUT_POST, $_POST['first_name'], FILTER_SANITIZE_STRING);
        $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));

        filter_input(INPUT_POST, $_POST['last_name'], FILTER_SANITIZE_STRING);
        $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));

        filter_input(INPUT_POST, $_POST['bus_name'], FILTER_SANITIZE_STRING);
        $bus_name = mysqli_real_escape_string($dbc, trim($_POST['bus_name']));
            
        filter_input(INPUT_POST, $_POST['describe'], FILTER_SANITIZE_STRING);
        $describe = mysqli_real_escape_string($dbc, trim($_POST['describe']));

        filter_input(INPUT_POST, $_POST['mission'], FILTER_SANITIZE_STRING);
        $mission = mysqli_real_escape_string($dbc, trim($_POST['mission']));

        filter_input(INPUT_POST, $_POST['mission'], FILTER_SANITIZE_STRING);
        $mission = mysqli_real_escape_string($dbc, trim($_POST['mission']));

        filter_input(INPUT_POST, $_POST['achievements'], FILTER_SANITIZE_STRING);
        $achievements = mysqli_real_escape_string($dbc, trim($_POST['achievements']));

        filter_input(INPUT_POST, $_POST['links'], FILTER_SANITIZE_STRING);
        $links = mysqli_real_escape_string($dbc, trim($_POST['links']));

        filter_input(INPUT_POST, $_POST['extra'], FILTER_SANITIZE_STRING);
        $extra = mysqli_real_escape_string($dbc, trim($_POST['extra']));

            
        filter_input(INPUT_POST, $_POST['email'], FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output_error_email = true;
        }

            
        if ($output_error_email) {
            echo  "<img class='img-thumbnail img-responsive' src='images/whoops.png'><br><p style='color:red;'>Hello, <br>$first_name $last_name<br><b>$email is not a valid email address. <br>";
        } else {
            $query = "INSERT INTO marketplace_uploads (`id`, `first_name`, `last_name`, `email`, `bus_name`, `describe`, `mission`, `achievements`, `links`, `extra`) VALUES (NULL, '$first_name', '$last_name', '$email', '$bus_name', '$describe', '$mission', '$achievements', '$links', '$extra');";

            mysqli_query($dbc, $query);
            $form_dissapear = true;
        }
    }
}

    
    if ($form_dissapear) {
        echo "Wikid";
        mysqli_close($dbc);
        exit;
    } else {
        ?>
<body>
 <script>
   
$(document).ready(function() {
      $(".questionaire").hide()
      $(".paste").hide()
       
      $(".yes_button").click(function() {
      $(".questionaire").toggle(2500);
     //$(".paste").show(2500)
      });
       
      $(".no_button").click(function(){
    
     $(".paste").toggle(900);
      });
      });
</script>
	<!-- Marketplace HTML form -->
	
	
		<form enctype="multipart/form-data" name="htmlform"  method="post" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			
			<div class="form-group row ">
  <label for="first_name" class="col-xs-2 col-form-label">First Name*</label>
  <div class="col-xs-10">
    <input class="form-control form-control-warning" required="required" id="inputWarning1" name="first_name" maxlength="50" size="30" placeholder="Required!" value="<?php  if (isset($_POST['first_name'])) {
            echo strip_tags($_POST['first_name']);
        } ?>" type="text">
  </div>
</div>

<div class="form-group row ">
  <label for="last_name" class="col-xs-2 col-form-label">Last Name*</label>
  <div class="col-xs-10">
    <input class="form-control " required="required" style="float: left" name="last_name" maxlength="50" size="30" placeholder="Required!" value="<?php  if (isset($_POST['last_name'])) {
            echo strip_tags($_POST['last_name']);
        } ?>">
  </div>
</div>
<div class="form-group row ">
  <label for="email" class="col-xs-2 col-form-label">Email*</label>
  <div class="col-xs-10">
    <input class="form-control " name="email" required="required" maxlength="60" size="30" placeholder="Required!" value="<?php  if (isset($_POST['email'])) {
            echo strip_tags($_POST['email']);
        } ?>" type="text">
  </div>
</div>
<div class="form-group">
  <label for="bus_name">Name of your organisation:*
    <br>
  </label>
  <br>
  <input class="form-control" name="bus_name" required="required" placeholder="Required!" value="<?php  if (isset($_POST['bus_name'])) {
            echo strip_tags($_POST['bus_name']);
        } ?>" type="text">

</div>
<br>

<div class="row"><p>
<div class="col-xs-8">
If you already have a promotional text you would like us to use click here.</div><button class="btn btn-primary no_button col-xs-4" id="no_button" type="button" name="no" value="no">I have my own article</button><br>
</div>
<div class="row ">
<div class="col-xs-8">
Otherwise, click here to fill out our questionnaire and we will write the article for you.</div>
<button class="btn btn-primary yes_button col-xs-4" id="yes_button" type="button" name="yes" value="yes">Fill out questionnaire</button>

</p>
</div>

  <br class="questionaire paste">
  


    <label class="questionaire" for="describe" id="yes">Describe exactly you sell or do.</label>
    <br class="questionaire">
    <textarea class="form-control questionaire" id="yes" style="float: none;" name="describe" maxlength="10000" cols="68" rows="8" placeholder="What's up?" value="" type="text">
      <?php if (isset($_POST['describe'])) {
            echo strip_tags($_POST['describe'], ENT_QUOTES);
        } ?>
    </textarea>


    <label class="questionaire" for="comments" id="yes">
      <br class="questionaire">How long have you been doing it and how would you describe your philosophy or mission statement. What makes it unique?</label>
    <textarea class="form-control questionaire" id="yes" data-gramm="" style=""float: none;" name="mission" maxlength="10000" cols="68" rows="8" placeholder="What's up?" value="">
      <?php if (isset($_POST['mission'])) {
            echo strip_tags($_POST['mission'], ENT_QUOTES);
        } ?>
    </textarea>


    <br class="questionaire">
    <label class="questionaire" for="comments" id="yes">
      <br>Tell me something cool about yourself, services and achievements.</label>
    <br class="questionaire">
    <textarea class="questionaire form-control" id="" style="float: none;" name="achievements" maxlength="10000" cols="68" rows="8" placeholder="What's up?" value=""><?php if (isset($_POST['achievements'])) {
            echo strip_tags($_POST['achievements'], ENT_QUOTES);
        } ?></textarea>






    <br class="questionaire">
    <label class="questionaire" id="" for="urls">
      <br>Copy your links here:</label>
    <br class="questionaire">
    <textarea class="questionaire form-control" style="float: none;" name="links" maxlength="10000" cols="68" rows="5" placeholder="What's up?"><?php if (isset($_POST['links'])) {
            echo strip_tags($_POST['links'], ENT_QUOTES);
        } ?></textarea><br>




    
   
    
    
    <label class="paste" id="" for="comments" id="yes">
      Copy your self written article here.
    </label>
    
    <textarea class="paste form-control" id="" data-gramm="" style="float: none;" name="extra" maxlength="10000" cols="68" rows="8" placeholder="What's up?" value="" ><?php if (isset($_POST['extra'])) {
            echo strip_tags($_POST['extra'], ENT_QUOTES);
        } ?></textarea></label><br><br>

     <label for="uploads" class="input-group questionaire paste " id="">
     Upload your images. Only jpg, jpeg, png files accepted 2mb or smaller. Maximum 10 files:<br></label>
    <input  id="" class="btn btn-primary paste questionaire  " name="files[]" multiple type="file"><br><br>
   
   
    <input class="btn btn-primary questionaire paste" name="submit" id="submit" value="Submit" type="submit">
  </div>
</div>

</div>
</form>


<?php

    }
 ?>

  </body>

		
					<!-- 
					<p>
						
						<br>
						<label for="comments"><br> <br>How long have you been doing it and
							how would you describe your philosophy or mission statement. What
							makes it unique?</label><br>
						<textarea data-gramm="" style="float: none;" name="mission"
							maxlength="10000" cols="68" rows="8" placeholder="What's up?"
							value="" required="required"></textarea>
						<br> <label for="comments"><br>Tell me something cool about
							yourself, services and achievements.</label><br>
						<textarea style="float: none;" name="achievements"
							maxlength="10000" cols="68" rows="8" placeholder="What's up?"
							value="" required="required"></textarea>
						
					</p>
					<div>
						<p>
							<label for="uploads"><br> <br>Upload your images. Only jpg, jpeg,
								png files accepted 2mb or smaller. Maximum 10 files:</label>
						</p>
						<input name="files[]" multiple type="file">
					</div>
					<div>
						<p>
							<label for="comments">Message to admin *</label> <br>
							<textarea data-gramm="" name="extra" maxlength="1000" cols="68"
								rows="10" placeholder="What's up?" value="" required="required"></textarea>
						</p>
					</div>
					<div colspan="2" style="text-align: center">
		  			<div class="g-recaptcha" data-sitekey="6LdMTSETAAAAANCTNvekuc8iitEvmEMWk4pK0hm7"></div>
				-->