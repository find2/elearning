<?php
	if (isset($_POST['id']) && isset($_POST['id']) != "") {
	    require 'lib.php';
	    $id= $_POST['id'];
	 
	    $object = new CRUD();
	    $classes=$object->Get_Id_Class($id);
	    foreach ($classes as $class) {
	    	$id_class=$class['id_class'];
	    }
	    $object->Delete_Class($id_class);
	    $object->Enroll_Delete($id);
	}
?>