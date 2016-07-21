<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>next demo</title>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
 
 <div>
 	<div>
		<p>Hello</p>
		<p class="selected">Hello Again</p>
		<p>Hello</p>
 	</div>
 </div>
<div><span>And Again</span></div>
 
<script>
$( "div" ).next("div").next.("p").next( ".selected" ).css( "background", "yellow" );
</script>
 
</body>
</html>