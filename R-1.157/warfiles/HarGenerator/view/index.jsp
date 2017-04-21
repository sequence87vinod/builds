<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Asynchronous file Upload in Java Web Application</title>
<script src="jquery-1.8.2.js"></script>
<script src="jquery.ajaxfileupload.js"></script>
<script language="Javascript">
$(document).ready(function(){	
	 $('input[type="file"]').ajaxfileupload({
	      'action': 'UploadFile',	      	    
	  'onComplete': function(response) {	        
	        $('#upload').hide();
	        alert("File SAVED!!");
	      },
	      'onStart': function() {
	        $('#upload').show(); 
	      }
	 });
});
</script>
<style type="text/css">
.centered{
width:100%;
margin-left:auto;
margin-right:auto;
text-align:center;
}
</style>
</head>
<body>
<form>
<div class="centered">
 <h2 style="text-align:center;">Asynchronous(AJAX Style) File upload in Java Web Application</h2>
 <p style="text-align:center;">Demo by Priya Darshini - Tutorial @ <a href="http://www.programming-free.com/2013/06/ajax-file-upload-java-iframe.html">Programmingfree</a></p>                                   
 <input type="file" name="datafile" /><br/>
 <div id="upload" style="display:none;">Uploading..</div>
</div>
</form>
</body>
</html>