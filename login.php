 <?php
session_start();
include 'connect.php';

if(isset($_POST['login-btn'])){
		$user= $_POST['username'];
		$pass= $_POST['password'];
		$pass=md5($pass);
		$sql="SELECT * FROM users WHERE username='$user' AND password='$pass'";
		$res=$conn->query($sql);
		if($res->num_rows>0){
			$row=$res->fetch_assoc();
			$_SESSION['e_level']=$row['level'];
			$_SESSION['e_username']=$row['username'];
			$_SESSION['e_name']=$row['name'];
			$_SESSION['e_monarch']=$row['monarch'];
			$_SESSION['e_user_id']=$row['id'];
			if($_SESSION['e_level']=="1"){
				echo "<script>
					location.href='lecturer/';
				</script>";
			}
      elseif($_SESSION['e_level']=="2"){
				echo "<script>
					location.href='student/';
				</script>";
			}
			else{
				echo "<script>
					location.href='admin/';
				</script>";
			}

			//header('location:results.php');
		}
		else{
			echo "<script>alert('Failed to Login');
			location.href='index.php';
			</script>";
		}
	}

if(isset($_POST['logout-btn'])){
	unset($_SESSION['e_level']);
	unset($_SESSION['e_username']);
	unset($_SESSION['e_name']);
	unset($_SESSION['e_monarch']);
	unset($_SESSION['e_id']);
	header('location:index.php');
	echo "<script>
		location.href='index.php';
	</script>";
}

if(isset($_POST['change-btn'])){
	$user= $_POST['username'];
	$old_pass= $_POST['old_password'];
	$old_pass=md5($old_pass);
	$new_pass= $_POST['new_password'];
	$new_pass=md5($new_pass);
	$sql="SELECT * FROM users WHERE username='$user' AND password='$old_pass'";
	$res=$conn->query($sql);
	if($res->num_rows>0){
		$sql2="UPDATE users SET password='$new_pass' WHERE username='$user'";
		$res2=$conn->query($sql2);
		echo "<script>alert('Succeed to Change Password');
			location.href='change-password.php';
		</script>";
	}
	else{
		echo "<script>alert('Failed to Change Password');
			location.href='change-password.php';
		</script>";
	}
}

$conn->close();
?>
