<?php

require __DIR__ . '/db_connection.php';

class CRUD
{

    protected $db;

    function __construct()
    {
        $this->db = DB();
    }

    function __destruct()
    {
        $this->db = null;
    }


	public function monarch($level){
		if($level=="01")
			$monarch="Dalung";
		else if($level=="02")
			$monarch="Candidasa";
		else if($level=="04")
			$monarch="Gianyar";
		else if($level=="03")
			$monarch="Singaraja";
		else if($level=="05")
			$monarch="Negara";
		return $monarch;
	}


	//---------------------------Home CONTENT------------------------------------------
	public function Create_Post_Modal($username, $monarch){
		$query = $this->db->prepare("SELECT class.id AS id, class.class_name AS class_name FROM enrolled_user, users, class
        	 WHERE users.username= :username AND class.monarch= :monarch AND enrolled_user.id_user=users.id AND enrolled_user.id_class=class.id");
		$query->bindParam("username", $username, PDO::PARAM_STR);
    $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        $data_code="";
        if(count($codes)>0){
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['id'] .'"">'. $code['class_name'] .'</option>';
        	}
        }

		$data='
		<div class="modal fade" id="create_post_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Create Post</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<label for="nama">Title Post</label>
							<input type="text" id="title_post" placeholder="Title Post" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="code">Post to Subject:</label>
		                    <select name="code" id="code" class="form-control">
								<option value="">Choose Subject:</option>
								'. $data_code .'
							</select>
		                </div>
		                <div class="adjoined-bottom">
							<div class="grid-container">
								<div class="grid-width-100">
									<div id="editor">
										<h1>Hello world!</h1>
									</div>
								</div>
							</div>
						</div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="posting()">Post</button>
		            </div>
		        </div>
		    </div>
		</div>
		<script>
			initSample();
		</script>
		';

	return $data;
	}

	public function Read_Post($id_class, $monarch){
		$query = $this->db->prepare("SELECT users.username AS username, posts.date_created AS date_created, posts.description AS description, posts.id AS post_id FROM posts, users
        	 WHERE posts.id_class= :id_class AND users.monarch= :monarch AND posts.id_user=users.id
        	 ORDER BY posts.id DESC ");
        /*$query = $this->db->prepare("SELECT * FROM posts
        	 WHERE id_class LIKE '%". $monarch ."%'
        	 ORDER BY id DESC ");*/
        $query->bindParam("id_class", $id_class, PDO::PARAM_STR);
        $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Delete_Post($id)
    {
        $query = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $query->bindParam("id", $id, PDO::PARAM_STR);
        $query->execute();
    }

    public function Read_Comment($id, $monarch){
        $query = $this->db->prepare("SELECT users.username AS username, comment_tb.date_created AS date_created, comment_tb.description AS content, comment_tb.id AS comment_id FROM comment_tb, users
        	 WHERE id_posts= :id AND users.monarch= :monarch AND comment_tb.id_user=users.id ORDER BY comment_tb.date_created");
		$query->bindParam("id", $id, PDO::PARAM_STR);
    $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Header_Post($username, $monarch){
      $query = $this->db->prepare("SELECT class.id AS id, class.class_name AS class_name FROM enrolled_user, users, class
          	 WHERE users.username= :username AND class.monarch= :monarch AND enrolled_user.id_user=users.id AND enrolled_user.id_class=class.id");
  		$query->bindParam("username", $username, PDO::PARAM_STR);
      $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
      $query->execute();
      $codes = array();
      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $codes[] = $row;
      }
      $data_code="";
      if(count($codes)>0){
        foreach($codes as $code){
          $data_code.='<option value="'. $code['id'] .'"">'. $code['class_name'] .'</option>';
        }
      }
    	$data='
			<section class="content-header">
				<h1>
					Post
					<small>Discussion</small>
				</h1>

				</br>
				<div class="pull-left">
					<button class="btn btn-success btn-md" data-toggle="modal" data-target="#create_post_modal">Create Post</button>
          <label for="code_class">Post to Subject:</label>
          <select name="code_class" id="code_class" class="form-control" onchange="show_post()">
            <option value="">Choose Subject:</option>
            '. $data_code .'
          </select>
				</div>

		</section></br></br>
		    <section>
				<div id="post_content" class="row">
		';
		return $data;
    }
	public function Post($username, $date_created, $description, $id){
		$data='

					<div class="col-lg-8 col-sm-12 pull-left">
						<!-- Box Comment -->
						<div class="box box-widget collapsed-box">
						<div class="box-header with-border">
							<div class="user-block">
								<img class="img-circle" src="dist/img/avatar5.png" alt="User Image">
								<span class="username">Posted By : '. $username .'</a></span>
								<!--<span class="username"><a href="#">'. $description .'</a></span>-->
								<span class="description">Posted On '. $date_created .'</span>
							</div>
							<!-- /.user-block -->
							<div class="box-tools">

								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
							<!-- /.box-tools -->
						</div>
							<!-- /.box-header -->
							<div class="box-body">
							'. $description .'
							</div>
							<!-- /.box-body -->
						';
						//<button type="button" class="btn btn-box-tool" onclick="delete_post('. $id .')"><i class="fa fa-trash"></i></button>
	return $data;
	}

	public function Write_Comment($posts_id, $user_id, $date_created, $desciption){
		$query = $this->db->prepare("INSERT INTO comment_tb (description, id_user, id_posts, date_created)
			VALUES (:description, :id_user, :id_post, :date_created)");
        $query->bindParam("description", $desciption, PDO::PARAM_STR);
        $query->bindParam("id_user", $user_id, PDO::PARAM_STR);
        $query->bindParam("id_post", $posts_id, PDO::PARAM_STR);
        $query->bindParam("date_created", $date_created, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Write_Post($description, $id_class, $date_created, $id_user){
		$query = $this->db->prepare("INSERT INTO posts (description, id_class, date_created, id_user)
			VALUES (:description, :id_class, :date_created, :id_user)");
        $query->bindParam("description", $description, PDO::PARAM_STR);
        $query->bindParam("id_class", $id_class, PDO::PARAM_STR);
        $query->bindParam("id_user", $id_user, PDO::PARAM_STR);
        $query->bindParam("date_created", $date_created, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Comment_Post($username, $date_created, $description, $post_id){
		$data='
						<div class="box-footer box-comments">
						  <div class="box-comment">
							<!-- User image -->
							<img class="img-circle img-sm" src="dist/img/avatar5.png" alt="User Image">

							<div class="comment-text">
								  <span class="username">
									'. $username .':
									<span class="text-muted pull-right">'. $date_created .'</span>
								  </span><!-- /.username -->
							   '. $description .'
							</div>
							<!-- /.comment-text -->
						  </div>
						 </div>
					';
		return $data;
	}

	public function End_Post($id){
		$data='
						<!-- /.box-footer -->
						<div class="box-footer">
						  <form action="" method="post">
							<img class="img-responsive img-circle img-sm" src="dist/img/avatar5.png" alt="Alt Text">
							<!-- .img-push is used to add margin to elements next to floating images -->
							<div class="img-push">
							  <input type="text" id="t_'. $id .'" class="form-control input-sm" placeholder="Press enter to post comment" >
                <button id="b_'. $id .'" type="button" class="btn btn-default" onclick="post_comment('. $id .')">Post Comment</button>
							</div>
						  </form>
						</div>
						<!-- /.box-footer -->
					  </div>
					  <!-- /.box -->
					</div>
			';
		return $data;
	}

	//---------------------------End Home CONTENT------------------------------------------
	//---------------------------Start Enroll------------------------------------------

	//---------------------------End Enroll------------------------------------------
	public function Enroll_Modal(){
		$data='
		<div class="modal fade" id="enroll_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Class Enrollment</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<label for="code">Code Subject:</label>
							<input type="text" id="code" placeholder="Code Subject" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="password">Enrollment Key:</label>
							<input type="password" id="password" placeholder="" class="form-control"/>
						</div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="enroll_class()">Enroll</button>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="modal fade" id="create_class_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Create New Class</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<label for="code">Code Subject:</label>
							<input type="text" id="code_class" placeholder="Code Subject" class="form-control"/>
						</div>
						<div class="form-group">
							<label for="password">Enrollment Key:</label>
							<input type="password" id="password_class" placeholder="" class="form-control"/>
						</div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="create_class()">Create</button>
		            </div>
		        </div>
		    </div>
		</div>
		';

	return $data;
	}

	public function Header_Enroll(){
    	$data='
				<h1>
					Enrollment
					<small>Class</small>
				</h1>
		';
		$data.='
		</br>
			<div class="pull-left">
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#enroll_modal">Enroll To Class</button>
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#create_class_modal">Create Class</button>
			</div>
		</br></br>
		<div class="row text-center"><h2> Class That You Already Enrolled:</h2></div>
			<div class="row x_panel"><div class="x_title">


				<table id="datatable" class="table table-striped table-bordered">
	              <thead>
	                <tr>
	                  <th>No.</th>
	                  <th>Code</th>
	                  <th>Class Name</th>
	                  <th>Date Enrolled</th>
	                  <th>Delete Enrolled Class</th>
	                </tr>
	              </thead>
	              <tbody>
		';
		return $data;
    }

    public function Data_Enroll($number, $code, $date_created, $id, $class_name){
    	$data='
    		<tr>
              <td>'. $number .'</td>
              <td>'. $code .'</td>
              <td>'. $class_name .'</td>
              <td>'. $date_created .'</td>
              <td><button class="btn btn-danger btn-sm" onclick="delete_enrolled_class(' . $id . ')">Delete</button>
              </td>
            </tr>';
        return $data;
    }

    public function Data_Enroll_Table($username){
    	$query = $this->db->prepare("SELECT enroll.code AS code, enrolled_user.date_created AS date_created, class.class_name AS class_name, enrolled_user.id AS id FROM enrolled_user, users, class, enroll
        	 WHERE users.username= :username AND enrolled_user.id_user=users.id AND enrolled_user.id_enroll=enroll.id AND enrolled_user.id_class=class.id");
    	//$query = $this->db->prepare("SELECT * FROM enroll_user
        	 //WHERE username= :username");
		$query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Read_Enroll($username){
        $query = $this->db->prepare("SELECT * FROM users
        	 WHERE username= :username");
		$query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Enroll_Class($id_enroll, $id_user, $date, $id_class, $monarch)
    {
       	$query = $this->db->prepare("INSERT INTO enrolled_user (id_user, id_enroll, date_created, id_class, monarch)
			VALUES ( :id_user, :id_enroll, :date_created, :id_class, :monarch)");
        $query->bindParam("id_user", $id_user, PDO::PARAM_STR);
        $query->bindParam("id_enroll", $id_enroll, PDO::PARAM_STR);
        $query->bindParam("date_created", $date, PDO::PARAM_STR);
        $query->bindParam("id_class", $id_class, PDO::PARAM_STR);
        $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
    }

    public function Enroll_Class_Validate($code, $password, $username, $class_name, $monarch){
        $query = $this->db->prepare("SELECT code FROM enroll WHERE code= :code AND password= :password");
		$query->bindParam("code", $code, PDO::PARAM_STR);
		$query->bindParam("password", $password, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        if(count($data)>0){
        	$result= "1";//Belum Enroll

        	$query2 = $this->db->prepare("SELECT users.username AS username, enroll.code AS code FROM enrolled_user, users, enroll WHERE enroll.code= :code2 AND users.username= :username AND enrolled_user.id_user=users.id AND enrolled_user.id_enroll=enroll.id");
			$query2->bindParam("code2", $code, PDO::PARAM_STR);
			$query2->bindParam("username", $username, PDO::PARAM_STR);
	        $query2->execute();
	        $data2 = array();
	        while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
	            $data2[] = $row2;
	        }
	        if(count($data2)>0){
	        	$result= "2"; // already enroll belum ad kelas
	        	$query3 = $this->db->prepare("SELECT users.username AS username, class.class_name AS class_name, class.monarch AS monarch FROM enrolled_user, users, class WHERE class.class_name= :class_name AND users.username= :username AND enrolled_user.monarch= :monarch AND enrolled_user.id_user=users.id AND enrolled_user.id_class=class.id");
				$query3->bindParam("class_name", $class_name, PDO::PARAM_STR);
				$query3->bindParam("username", $username, PDO::PARAM_STR);
				$query3->bindParam("monarch", $monarch, PDO::PARAM_STR);
		        $query3->execute();
		        $data3 = array();
		        while ($row3 = $query3->fetch(PDO::FETCH_ASSOC)) {
		            $data3[] = $row3;
		        }
		        if(count($data3)>0)
		        	$result= "3"; // sudah ada kleas sudah enroll
		        else
		        	$result= "1"; // belum enroll belum ada kelas
	        }

        }
        else
        	$result= "0";
        return $result;
    }

    public function Enroll_Create_Class($code, $password, $username, $date_created){
    	$query = $this->db->prepare("INSERT INTO enroll (code, password, username, date_created )
			VALUES (:code, :password, :username, :date_created)");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->bindParam("password", $password, PDO::PARAM_STR);
        $query->bindParam("code", $code, PDO::PARAM_STR);
        $query->bindParam("date_created", $date_created, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
    }

    public function Enroll_Delete($id){
    	$query = $this->db->prepare("DELETE FROM enrolled_user WHERE id = :id");
        $query->bindParam("id", $id, PDO::PARAM_STR);
        $query->execute();
    }

    public function Get_Id_Enroll($code){
        $query = $this->db->prepare("SELECT id FROM enroll
        	 WHERE code= :code");
		$query->bindParam("code", $code, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Create_Class_Name($id_enroll, $class_name, $id_user, $monarch){
    	$query = $this->db->prepare("INSERT INTO class (id_enroll, class_name, id_user, monarch )
			VALUES (:id_enroll, :class_name, :id_user, :monarch)");
        $query->bindParam("id_enroll", $id_enroll, PDO::PARAM_STR);
        $query->bindParam("class_name", $class_name, PDO::PARAM_STR);
        $query->bindParam("id_user", $id_user, PDO::PARAM_STR);
        $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
    }

    public function Get_Id_Class($id_enrolled){
        $query = $this->db->prepare("SELECT id_class FROM enrolled_user
        	 WHERE id= :id_enrolled");
		$query->bindParam("id_enrolled", $id_enrolled, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Delete_Class($id){
    	$query = $this->db->prepare("DELETE FROM class WHERE id = :id");
        $query->bindParam("id", $id, PDO::PARAM_STR);
        $query->execute();
    }

    //ENROLL END

    //MATERIAL START
    public function Material_Modal($username){
		$query = $this->db->prepare("SELECT code FROM enroll_user
        	 WHERE username= :username");
		$query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        if(count($codes)>0){
        	$data_code="";
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['code'] .'"">'. $code['code'] .'</option>';
        	}
        }

		$data='
		<div class="modal fade" id="material_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Show Subject Material</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<label for="code">Show Subject:</label>
		                    <select name="code" id="code" class="form-control">
								<option value="">Choose Subject:</option>
								'. $data_code .'
							</select>
		                </div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="show_material_folder()">Post</button>
		            </div>
		        </div>
		    </div>
		</div>

		<h1>
			Subject
			<small>Material</small>
		</h1>
		</br>
			<div class="pull-left">
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#material_modal">Show Subject</button>
			</div>
		</br></br>
		';


	return $data;
	}



    //MATERIAL END

    //ASS START
    public function Ass_Modal($username){
		$query = $this->db->prepare("SELECT code FROM enroll_user
        	 WHERE username= :username");
		$query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        if(count($codes)>0){
        	$data_code="";
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['code'] .'"">'. $code['code'] .'</option>';
        	}
        }

		$data='
		<div class="modal fade" id="ass_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Show Assignment</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<label for="code">Show Subject:</label>
		                    <select name="code" id="code" class="form-control">
								<option value="">Choose Subject:</option>
								'. $data_code .'
							</select>
		                </div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="show_ass_folder()">Post</button>
		            </div>
		        </div>
		    </div>
		</div>

		<h1>
			Assignment
			<small></small>
		</h1>
		</br>
			<div class="pull-left">
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#ass_modal">Show Subject</button>
			</div>
		</br></br>
		';


	return $data;
	}



    //ASS END

    //Quiz START
    public function Quiz_Modal($username, $monarch){
		$query = $this->db->prepare("SELECT class.class_name AS class_name, class.id AS id_class FROM enrolled_user, users, class
        	 WHERE users.username= :username AND class.monarch= :monarch AND enrolled_user.id_user=users.id AND enrolled_user.id_class=class.id");
		$query->bindParam("username", $username, PDO::PARAM_STR);
    $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        $data_code="";
        if(count($codes)>0){
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['id_class'] .'"">'. $code['class_name'] .'</option>';
        	}
        }
        $total_question='';
        for($i=1;$i<=20;$i++)
        	$total_question.='<option value="'. $i .'">'. $i .'</option>';

		$data='
		<div class="modal fade" id="quiz_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Create Quiz</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="code">Choose Subject:</label>
				                    <select name="code" id="code" class="form-control" required >
										<option value="">--</option>
										'. $data_code .'
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="code_test">Quiz Name</label>
									<input type="input" id="code_test" placeholder="" class="form-control" required />
								</div>
							</div>
		                </div>
		                <div class="form-group">
		                	<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="question_mc_n">Number of Multiple Choices Questions:</label>
				                    <select name="question_mc_n" id="question_mc_n" class="form-control" onchange="set_question_mc()" required >
										<option value="0">0</option>
										'. $total_question .'
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="question_essay_n">Number of Essay Questions</label>
									<select name="question_essay_n" id="question_essay_n" class="form-control" onchange="set_question_essay()" required >
										<option value="0">0</option>
										'. $total_question .'
									</select>
								</div>
							</div>
		                </div>
		                <div id="mc_content"></div>
		                <div id="essay_content"></div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="create_quiz()">Create</button>
		            </div>
		        </div>
		    </div>
		</div>
		<h1>
			Quiz
			<small></small>
		</h1>
		</br>
			<div class="pull-left">
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#quiz_modal">Create Quiz</button>
				<button class="btn btn-success btn-md" data-toggle="modal" data-target="#show_quiz_modal">Show Quiz</button>
			</div>
		</br></br>
		';


	return $data;
	}

	public function Question_Field_Mc($number){
        $data='<label><h4>Multiple Choice</h4></label>';
        for($i=1;$i<=$number;$i++){
        	$data.='
	        	<div class="form-group">
					<label for="qm_">Question Number '. $i .':</label>
		    		<textarea id="qm_'. $i .'" rows="4" cols="30" class="form-control" required ></textarea>
		    	</div>
		    	<div class="form-group">
		    		<div class="row">
			    		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			    			<label for="ama_'. $i .'">Choice A:</label>
							<input type="input" id="ama_'. $i .'" placeholder="" class="form-control" required />
			    		</div>
			    		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			    			<label for="amb_'. $i .'">Choice B:</label>
							<input type="input" id="amb_'. $i .'" placeholder="" class="form-control" required />
			    		</div>
			    		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			    			<label for="amc_'. $i .'">Choice C:</label>
							<input type="input" id="amc_'. $i .'" placeholder="" class="form-control" required />
			    		</div>
			    		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			    			<label for="amd_'. $i .'">Choice D:</label>
							<input type="input" id="amd_'. $i .'" placeholder="" class="form-control" required />
			    		</div>
			    		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
				    		<label for="km_'. $i .'">Answer:</label>
			                <select name="km_'. $i .'" id="km_'. $i .'" class="form-control">
								<option value="a">A</option>
								<option value="b">B</option>
								<option value="c">C</option>
								<option value="d">D</option>
							</select>
						</div>
					</div>
		    	</div>
        	';
        }
		return $data;
	}

	public function Question_Field_Essay($number){
        $data='<label><h4>Essay</h4></label>';
        for($i=1;$i<=$number;$i++){
        	$data.='
	        	<div class="form-group">
					<label for="em_">Question Number '. $i .':</label>
		    		<textarea id="em_'. $i .'" rows="4" cols="30" class="form-control" required ></textarea>
		    	</div>
		    	<div class="form-group">
		    		<label for="ek_">Answer:</label>
	    			<textarea id="ek_'. $i .'" rows="4" cols="30" class="form-control" required ></textarea>
		    	</div>
        	';
        }
		return $data;
	}

	public function Write_Quiz($users_id, $class_id, $quiz_name, $duration, $date_created, $monarch, $number_mc, $number_e){
		$query = $this->db->prepare("INSERT INTO quiz (id_user, id_class, quiz_name, duration, date_created, monarch, total_question_mc, total_question_essay)
			VALUES (:users_id, :class_id, :quiz_name, :duration, :date_created, :monarch, :total_question_mc, :total_question_essay)");
        $query->bindParam("users_id", $users_id, PDO::PARAM_STR);
        $query->bindParam("class_id", $class_id, PDO::PARAM_STR);
        $query->bindParam("quiz_name", $quiz_name, PDO::PARAM_STR);
        $query->bindParam("duration", $duration, PDO::PARAM_STR);
        $query->bindParam("date_created", $date_created, PDO::PARAM_STR);
        $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->bindParam("total_question_mc", $number_mc, PDO::PARAM_STR);
        $query->bindParam("total_question_essay", $number_e, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Write_QA_MC($quiz_id, $question_number, $question_m, $answer_m){
		$query = $this->db->prepare("INSERT INTO qa_mc_quiz (id_quiz, question_number, question_mc, answer_mc)
			VALUES (:id_quiz, :question_number, :question_mc, :answer_mc)");
        $query->bindParam("id_quiz", $quiz_id, PDO::PARAM_STR);
        $query->bindParam("question_number", $question_number, PDO::PARAM_STR);
        $query->bindParam("question_mc", $question_m, PDO::PARAM_STR);
        $query->bindParam("answer_mc", $answer_m, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Write_QA_Essay($quiz_id, $question_number, $question_e, $answer_e){
		$query = $this->db->prepare("INSERT INTO qa_essay_quiz (id_quiz, question_number, question_essay, answer_essay)
			VALUES (:id_quiz, :question_number, :question_essay, :answer_essay)");
        $query->bindParam("id_quiz", $quiz_id, PDO::PARAM_STR);
        $query->bindParam("question_number", $question_number, PDO::PARAM_STR);
        $query->bindParam("question_essay", $question_e, PDO::PARAM_STR);
        $query->bindParam("answer_essay", $answer_e, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Write_MC_Quiz($qa_mc_id, $ama, $amb, $amc, $amd){
		$query = $this->db->prepare("INSERT INTO mc_quiz (id_qa_mc_quiz, answer_a, answer_b, answer_c, answer_d)
			VALUES (:id_qa_mc_quiz, :answer_a, :answer_b, :answer_c, :answer_d)");
        $query->bindParam("id_qa_mc_quiz", $qa_mc_id, PDO::PARAM_STR);
        $query->bindParam("answer_a", $ama, PDO::PARAM_STR);
        $query->bindParam("answer_b", $amb, PDO::PARAM_STR);
        $query->bindParam("answer_c", $amc, PDO::PARAM_STR);
        $query->bindParam("answer_d", $amd, PDO::PARAM_STR);
        $query->execute();
        return $this->db->lastInsertId();
	}

	public function Validate_Quiz($quiz_name, $class_id, $monarch){
		$query = $this->db->prepare("SELECT quiz_name, id_class FROM quiz WHERE quiz_name= :quiz_name AND id_class= :id_class AND monarch= :monarch");
		$query->bindParam("quiz_name", $quiz_name, PDO::PARAM_STR);
		$query->bindParam("id_class", $class_id, PDO::PARAM_STR);
    $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        if(count($data)>0){
        	$result = "1";
        }
        else{
        	$result= "0";
        }
        return $result;
	}

	public function Show_Quiz_Modal($username, $monarch){
		$query = $this->db->prepare("SELECT DISTINCT class.class_name AS class_name, enrolled_user.id_class AS id_class FROM enrolled_user, class, users
        	 WHERE users.username= :username AND class.monarch= :monarch AND enrolled_user.id_class=class.id AND enrolled_user.id_user=users.id");
		$query->bindParam("username", $username, PDO::PARAM_STR);
    $query->bindParam("monarch", $monarch, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        $data_code="";
        if(count($codes)>0){
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['id_class'] .'"">'. $code['class_name'] .'</option>';
        	}
        }

		$data='
		<div class="modal fade" id="show_quiz_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title" id="myModalLabel">Show Quiz</h4>
		            </div>
		            <div class="modal-body">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="class_code">Choose Subject:</label>
				                    <select name="class_code" id="class_code" class="form-control" onchange="set_quiz_question()" required >
										<option value="">--</option>
										'. $data_code .'
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label for="quiz_code">Quiz Name</label>
									<select name="quiz_code" id="quiz_code" class="form-control" required >
										<option value="">--</option>
									</select>
								</div>
							</div>
		                </div>

		                <div id="quiz_content"></div>
					</div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button type="button" class="btn btn-primary" onclick="show_question()">Show</button>
		            </div>
		        </div>
		    </div>
		</div>
		';


	return $data;
	}

  // Dipanggil dari lecture.js funtion set_quiz_question
	public function Set_Quiz_Question($class_id){
		$query = $this->db->prepare("SELECT id, quiz_name FROM quiz
        	 WHERE id_class= :id_class");
		$query->bindParam("id_class", $class_id, PDO::PARAM_STR);
        $query->execute();
        $codes = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $codes[] = $row;
        }
        $data_code='<option value="">--</option>';
        if(count($codes)>0){
        	foreach($codes as $code){
        		$data_code.='<option value="'. $code['id'] .'"">'. $code['quiz_name'] .'</option>';
        	}
        }
        return $data_code;
	}

  // Dipanggil dari lecture.js funtion show_question
	public function Show_Quiz_Question($quiz_id, $quiz_name){
        $data='<label><h4>Question</h4></label>';
        $query = $this->db->prepare("SELECT quiz.id AS quiz_id, quiz.quiz_name AS quiz_name, qa_mc_quiz.question_number AS question_number_mc, qa_mc_quiz.question_mc, mc_quiz.answer_a, mc_quiz.answer_b, mc_quiz.answer_c, mc_quiz.answer_d FROM quiz, qa_mc_quiz, mc_quiz WHERE quiz.id= :quiz_id AND qa_mc_quiz.id_quiz=quiz.id AND mc_quiz.id_qa_mc_quiz=qa_mc_quiz.id GROUP BY qa_mc_quiz.question_number");
		$query->bindParam("quiz_id", $quiz_id, PDO::PARAM_STR);
        $query->execute();
        $mc_quiz = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $mc_quiz[] = $row;
        }
        $data_code='<h2>'. $quiz_name .'</h2>';
        if(count($mc_quiz)>0){
          $data_code.='<h4>Multiple Choice</h4>';
        	foreach($mc_quiz as $mc){
        		$data_code.='<p>'. $mc['question_number_mc'] . $mc['question_mc'] .'</p>
        					<p>'. $mc['answer_a'] .'  '. $mc['answer_b'] .'  '. $mc['answer_c'] .'  '. $mc['answer_d'] .'</p>';
        	}
        }// End Multiple Choice
        // Start Essay
        $query2 = $this->db->prepare("SELECT quiz.id AS quiz_id, quiz.quiz_name AS quiz_name, qa_essay_quiz.question_number AS question_number_essay, qa_essay_quiz.question_essay FROM quiz, qa_essay_quiz WHERE quiz.id= :quiz_id AND qa_essay_quiz.id_quiz=quiz.id GROUP BY qa_essay_quiz.question_number");
		$query2->bindParam("quiz_id", $quiz_id, PDO::PARAM_STR);
        $query2->execute();
        $essay_quiz = array();
        while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
            $essay_quiz[] = $row2;
        }
        if(count($essay_quiz)>0){
          $data_code.='<h4>Essay</h4>';
        	foreach($essay_quiz as $essay){
        		$data_code.='<p>'. $essay['question_number_essay'] . $essay['question_essay'] .'</p>';
        	}
        }// End Essay
		return $data_code;
	}

    //Quiz END
}


?>
