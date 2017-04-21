<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1"%>
    <%@ page import="java.io.File;"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Appedo Har View</title>
<!-- table style sheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	width:50%;
	align:center;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
	text-align:center;
}
</style>
<!-- Table goes in the document BODY -->


</head>
<body>
<center>
<table class="gridtable">
<thead><th>#Sl</th><th>FileName</th></thead>
<tbody>
<%
// Har files directory path here
String strHarFilesPath = "C:/harviewer/HarRepository"; 
String strHarFileviewrPath="http://54.227.155.150:8080/harviewer/index.php?path=HarRepository/";

String files;
File folder = new File(strHarFilesPath);
File[] listOfFiles = folder.listFiles();
int filecount=0;

for (int i = 0; i < listOfFiles.length; i++)
{
	  if (listOfFiles[i].isFile())
	  {
		  files = listOfFiles[i].getName();
		  if (files.endsWith(".har") || files.endsWith(".HAR"))
		  {
			  filecount++;
			 
			  

%>
<tr><td><%=filecount %></td><td><a href=<%=strHarFileviewrPath+files %> ><%=files %></a></td></tr>
<% } %>
<% } %>
<% } %>

<% if(filecount==0){%> 
<tr  ><td></td><td > No records found</td></tr>
<%} %>
</tbody>
</table>
</center>
</body>
</html>