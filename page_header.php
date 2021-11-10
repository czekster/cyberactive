<?php
include_once('globals.php');
if (isset($_SESSION['user']))
   $u = unserialize($_SESSION['user']);
?>
<!DOCTYPE html>
<html>
<title>Cyber-ActiVE</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/png" href="images/favicon-32-32.png">
<style>
html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif;}
.w3-sidebar {
  z-index: 3;
  width: 250px;
  top: 43px;
  bottom: 0;
  height: inherit;
}
hr.new1 {
  border-top: 1px solid black;
}
hr.big-divider {
	color: teal; 
	background: teal; 
	border: 0; 
	height: 2px; 
	margin: 1em 0 2em;
}
hr.graySmall
{
	color: gray; 
	background: gray; 
	border: 0; 
	height: 1px; 
	margin: 1em 0 2em;
}

<!-- table style -->
#standard-table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#standard-table td, #standard-table th {
  border: 1px solid #ddd;
  padding: 8px;
}

#standard-table tr:nth-child(even){background-color: #f2f2f2;}

#standard-table tr:hover {background-color: #ddd;}

#standard-table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

#strikeout {
   text-decoration: line-through;
}

</style>
