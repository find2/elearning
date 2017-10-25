var link="";
var post_id=0;
var quiz_id=0;

function loading(){
	$.post("php/loader.php", {
	}, function (data, status) {
		$("#main_content").html(data);
	});
}
//Home Start
function home() {
	loading();
	$.post("php/home.php", {
	}, function (data, status) {
		$("#main_content").html(data);
		$('.bxslider').bxSlider();
	});
}
//Home End

//Enrollment Start
function enrollment(){
	loading();
	$.post("php/enroll.php", {
	}, function (data, status) {
		//$().DataTable();
		$("#main_content").html(data);
	});
}

function enroll_class(){
	var id_class = $("#code").val();
	id_class = id_class.trim();
	var password = $("#password").val();
	password = password.trim();
	var class_name = $("#code option:selected").text();
	$.post("php/enroll_class_validate.php", {
		id_class:id_class,
		password:password
	}, function (data, status) {
		$("#enroll_modal").modal("hide");
		if(data==1)
			alert("Enrolled to Subject "+class_name+".");
		else if(data==2)
			alert("Already Enrolled Subject "+class_name+".");
		else
			alert("Failed to enroll.");
		enrollment();
	});
}

function create_class(){
	var code = $("#code_class").val();
	code = code.trim();
	var password = $("#password_class").val();
	password = password.trim();
	$.post("php/enroll_create_class.php", {
		code:code,
		password:password
	}, function (data, status) {
		$("#create_class_modal").modal("hide");
		if(data==0)
			alert("New class created.");
		else
			alert("Class already created, please use other code subject.");
		enrollment();
	});
}

function delete_enrolled_class(id){
	var conf = confirm("Delete the enrolled class?");
    if (conf == true) {
        $.post("php/enroll_delete.php", {
                id: id
            },
            function (data, status) {
            	enrollment();
            }
        );
    }
}
//Enrollment End

//Post Start
function post(){
	$.post("php/post.php", {
		// Need Input id_class
	}, function (data, status) {
		$("#main_content").html(data);

	});
}

function post_comment(id){
	//$(".box-footer").activateBox();

	var comment = $("#t_"+id).val();
	comment = comment.trim();
	if (comment == "") {
		console.log("NULL");
	}
	else {
		$.post("php/post_comment.php", {
			comment:comment,
			post_id:id
		}, function (data, status) {
			$("t_"+id).val("");
			//post_id=0;
			show_post();
		});
	}
//home();
}

/*function post_comment(event){
	var x = event.which;
	if(x==13){
		$(".box-footer").activateBox();

		var comment = $("#t_"+post_id).val();
		comment = comment.trim();
	    if (comment == "") {
	    }
	    else {
	        $.post("php/post_comment.php", {
				comment:comment,
				post_id:post_id
	        }, function (data, status) {
				$("#comment-box").val("");
				post_id=0;
	        });
	    }
		//home();
	}
}*/

/*function set_post_id(id){
	post_id=id;
}*/

/*
	dipanggil pada onchange dropbox pada menu utama post lecture untk merender data pada table post sesuai dengan class yg sdh d enroll
*/
function show_post(){
	var class_id = $("#code_class").val();
	class_id = class_id.trim();
	$.post("php/show_post.php", {
		class_id:class_id
	}, function (data, status) {
		$("#post_content").children().remove();
		$("#post_content").append(data);
	});
}
//Post End

//GENERAL MATERIAL START
function general_material(){
	loading();
	$.post("php/material.php", {
	}, function (data, status) {
		//$().DataTable();
		$("#main_content").html(data);
	});
}

function show_material_folder(){
	var material = $("#code").val();
	material = material.trim();
	location="../subject_material/"+material+"/index.php";
	$.post(location, {
	}, function (data, status) {
		//window.open(data);
		$("#main_content").html(data);
	});
}

//GENERAL MATERIAL END

//ASSIGNMENT START
function ass(){
	loading();
	$.post("php/ass.php", {
	}, function (data, status) {
		//$().DataTable();
		$("#main_content").html(data);
	});
}

function show_ass_folder(){
	var ass = $("#code").val();
	ass = ass.trim();
	location="../assignment/"+ass+"/index.php";
	$.post(location, {
	}, function (data, status) {
		//window.open(data);
		$("#main_content").html(data);
	});
}
//ASSIGNMENT END

//TEST AND QUIZ START
var number_mc=number_e=0;
function quiz(){
	loading();
	$.post("php/quiz.php", {
	}, function (data, status) {
		//$().DataTable();
		$("#main_content").html(data);
	});
}

function set_question_mc(){
	var number = $("#question_mc_n").val();
	number = number.trim();
	number_mc=number;
	var type="1";
	$.post("php/quiz_set_question_modal.php", {
		number:number,
		type:type
	}, function (data, status) {
		$("#mc_content").html(data);
	});
}

function set_question_essay(){
	var number = $("#question_essay_n").val();
	number = number.trim();
	number_e=number;
	var type="2";
	$.post("php/quiz_set_question_modal.php", {
		number:number,
		type:type
	}, function (data, status) {
		$("#essay_content").html(data);
	});
}

function create_quiz(){
	var class_code = $("#code").val();
	class_code = class_code.trim();
	var title = $("#code_test").val();
	title = title.trim();
	var duration = 600000; // get input user
	var qm = new Array(number_mc);
	var ama = new Array(number_mc);
	var amb = new Array(number_mc);
	var amc = new Array(number_mc);
	var amd = new Array(number_mc);
	var km = new Array(number_mc);
	for(var i=1; i<=number_mc; i++)
	{
		qm[i] = $("#qm_"+i).val().trim();
		ama[i] = $("#ama_"+i).val().trim();
		amb[i] = $("#amb_"+i).val().trim();
		amc[i] = $("#amc_"+i).val().trim();
		amd[i] = $("#amd_"+i).val().trim();
		km[i] = $("#km_"+i).val();
	}
	var qe = new Array(number_e);
	var ke = new Array(number_e);
	for(var i=1; i<=number_e; i++)
	{
		qe[i] = $("#em_"+i).val().trim();
		ke[i] = $("#ek_"+i).val().trim();
	}
	$.post("php/quiz_create.php", {
		class_code:class_code,
		title:title,
		qm:qm,
		ama:ama,
		amb:amb,
		amc:amc,
		amd:amd,
		km:km,
		qe:qe,
		ke:ke,
		duration:duration,
		number_mc:number_mc,
		number_e:number_e
	}, function (data, status) {
		if(data==0){
			alert("Quiz Created.");
			number_mc=0;
			number_e=0;
			quiz();
		}
		else if(data==1)
			alert("Quiz title already exist.");
	});
}

/*
	Melihatkan prtanyaan yg sdah dibuat oleh lecture sesuai dengan class
*/
function show_question(){
	var quiz_name = $("#quiz_code option:selected").text();
	quiz_name = quiz_name.trim();
	$.post("php/quiz_question.php", {
		quiz_id:quiz_id,
		quiz_name:quiz_name
	}, function (data, status) {
		$("#quiz_content").html(data);
	});
}

/*
	dipanggil pda onchange pda dropbox choose subject pda menu show quiz yang digunakan untuk merubah dropbox quiz name
*/
function set_quiz_question(){
	var class_id = $("#class_code").val();
	class_id = class_id.trim();
	$.post("php/set_quiz_question_modal.php", {
		class_id:class_id
	}, function (data, status) {
		$("#quiz_code").children().remove();
		$("#quiz_code").append(data);
	});
}

// Digunkan utk mnmpan id_quiz
function set_id_quiz(){
	quiz_id = $("#quiz_code").val();
	quiz_id = quiz_id.trim();
}

// Digunkan untuk mengumpulkan jawaban saat studen mnkan button submit
function submit_question(){
	var total_mc = $('#total_mc').val().trim();
	var total_essay = $('#total_essay').val().trim();
	var mc = new Array(total_mc);
	//var essay = new array(total_essay);
	for(var i=1; i<=total_mc; i++)
	{
		mc[i] = $('input[name=answer_'+i+']:checked').val().trim();
	}
	$.post("php/submit_question.php", {
		quiz_id:quiz_id,
		mc:mc,
		total_mc:total_mc
		//essay:essay
	}, function (data, status) {
	});
}
//TEST AND QUIZ END


$(function (){
	home();
  });
