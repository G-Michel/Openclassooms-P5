var form = document.querySelector(".form_surname_button");
	form.addEventListener("click",function(e){
		document.querySelector(".form_surname_class").classList.toggle('d-none');
});

var form = document.getElementsByClassName("form_name_button");
	form[0].addEventListener("click",function(e){
		document.querySelector(".form_name_class").classList.toggle('d-none');

		
});

var form = document.querySelector(".form_email_button");
	form.addEventListener("click",function(e){
		document.querySelector(".form_email_class").classList.toggle('d-none');

});

var form = document.querySelector(".form_password_button");
	form.addEventListener("click",function(e){
		document.querySelector(".form_password_class").classList.toggle('d-none');

});

var form = document.querySelector(".form_picture_button");
	form.addEventListener("click",function(e){
		document.querySelector(".form_picture_class").classList.toggle('d-none');

});