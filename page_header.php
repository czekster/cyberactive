<?php
include_once('globals.php');
if (isset($_SESSION['user']))
   $u = unserialize($_SESSION['user']);
?>
<!DOCTYPE html>
<html>
<title>cyberaCTIve</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="icon" type="image/png" href="images/favicon-32-32.png">
<link rel="stylesheet" type="text/css" href="stix-visualizer.css" />
<style>
  .selected {
    /* Drop-shadow SVG styling has to go here for compatibility reasons */
    filter: url("#drop-shadow");
  }
</style>
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

.stix-icon-sm {
  width: 24px;
  height: 24px;
  margin: 0px 3px;
}
table.grid td, table.grid td > form, table.grid td > p {
  text-align: center;
}
table.grid td > p {
  margin: 0;
  font-size: small;
}
table.grid td > p > span {
  font-size: smaller;
  color: gray;
}
table.grid td > form > input[type='image'] {
  border: 2px solid transparent;
  border-radius: 5px;
  background-color: transparent;
}
table.grid td > form > input[type='image']:hover, table.grid td > form > input[type='image']:focus {
  border: 2px solid teal;
  background-color: lightblue;
}
</style>
