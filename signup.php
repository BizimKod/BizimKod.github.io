<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .signup-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
    </style>
</head>
<body>
<?php
        $nameErr=$emailErr=$passwordErr="";
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        function kontrol($name,$email,$pass,$cpass){
            global $nameErr;
            global $emailErr;
            global $passwordErr;
            $x=$y=$z=$w=FALSE;
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $y=True;
                $emailErr="";

            }else{
                $emailErr="E-mail geçerli değil";
            }
            if((strlen($name) >= 8) and preg_match("/^[a-zA-Z-' ]*$/", $name)){
                $x=TRUE;
                $nameErr="";
            }else{
                $nameErr="Kullanıcı adı en az 8 karakter olmalı ve harften oluşmalı";
            }
            if(preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/', $pass)){
                $z=TRUE;
                $passwordErr="";
            }else {
                $passwordErr="Şifre en az 8 karakter olmalı,en az bir harf ve bir sayı içermelidir!";
            }
            if($cpass===$pass){
                $passwordErr="";
                $w=TRUE;
            }else{
                $passwordErr="şifre eşleşmiyor!";
            }

            if($z==TRUE and $y==TRUE and $x==TRUE and $w==TRUE){
                return TRUE;
            }else {
                return FALSE;
            }

        }
        session_start();

        include "connection.php";

        if (isset($_POST['register'])) {

        $name = test_input($_POST['username']);
        $email = test_input($_POST['email']);
        $pass = test_input($_POST['password']);
        $cpass = test_input($_POST['cpass']);


        $check = "select * from users where email='{$email}'";

        $res = mysqli_query($conn, $check);

        $passwd = password_hash($pass, PASSWORD_DEFAULT);

        $key = bin2hex(random_bytes(12));




        if (mysqli_num_rows($res) > 0) {
            echo "<div class='container'><div class='row justify-content-center'><div class='col-md-6 signup-container'>
            <p>This email is used, Try another One Please!</p>";

            echo "<a href='javascript:self.history.back()'><button class='btn btn-primary'>Go Back</button></a></div></div></div><br>";
        } 
        else {
            if(kontrol($name,$email,$pass,$cpass)){

                $sql = "INSERT INTO users (username,email,password) VALUES('$name','$email','$passwd')";
                if (mysqli_query($conn, $sql)) {

                    echo "<div class='container'><div class='row justify-content-center'>
                    <div class='col-md-6 signup-container'>
                    <p>You are register successfully!</p>
                    <a href='login.php'><button class='btn btn-primary'>Login Now</button></a>
                    </div></div></div><br>";

                    //echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
                }
                else{
                    echo "<div class='container'><div class='row justify-content-center'>
                    <div class='col-md-6 signup-container'>
                    <p>Error :".$sql."<br>". mysqli_error($conn)."</p>"."<a href='javascript:self.history.back()'><button class='btn btn-primary'>Go Back</button></a>
                    </div></div></div><br>";
                    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    //echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
                }  
                
            }
        }
        }

        ?>
        <!--<div class="links">
        Already have an account? <a href="login.php">Signin Now</a>
        </div>-->
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 signup-container">
            <h2 class="text-center mb-4">Sign Up</h2>
            <form action="#" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $_POST['username'] ?>" placeholder="Username" required>
                    <small id="usernameHelp" class="form-text text-danger"><?php echo $nameErr; ?></small>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $_POST['email'] ?>" required>
                    <small id="emailHelp" class="form-text text-danger"><?php echo $emailErr; ?></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"  required>
                    <small id="passwordHelp" class="form-text text-danger"><?php echo $passwordErr; ?></small>
                </div>
                <div class="form-group">
                    <label for="cpass">Confirm Password</label>
                    <input type="password" class="form-control" id="cpass" name="cpass" placeholder="Confirm Password" required>  <!-- Corrected ID -->
                    <small id="cpassHelp" class="form-text text-danger"><?php echo $cpassErr; ?></small>  <!-- Added error message for confirm password -->
                </div>
                <button type="submit" name="register" value="sign up" class="btn btn-primary btn-block">Sign Up</button>  <!-- Block button -->
            </form>
            <p>Üye iseniz buraya <a href="login.php">tıklayın</a>
        </div>
    </div>
</div>
        
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
        crossorigin="anonymous"></script>
        
</form>
</div>

</body>
</html>