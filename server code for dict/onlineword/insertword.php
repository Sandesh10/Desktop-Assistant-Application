<html>
<head>
<style>
.drop
{
	display: inline-block;
}
</style>
<meta charset="utf-8">
    <link href="src/netjsongraph.css" rel="stylesheet">
    <!-- theme can be easily customized via css -->
  
     <style type="text/css">
        

        .njg-overlay, .njg-metadata{
            width: auto;
            height: auto;
            min-width: 200px;
            max-width: 400px;
            border: 1px solid #000;
            border-radius: 2px;
            background: rgba(0, 0, 0, 0.5);
            top: 10px;
            right: 10px;
            padding: 0 15px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #fff
        }

        .njg-metadata{
            left: 10px;
            right: auto;
            max-width: 500px;
            
        }

        #network-graph{
            width: 1000px;
            height:400px;
            margin: -10px auto 0;
            border: 2px solid #000;
          
        }

        .njg-node {
            fill: #8DFD5C;
            stroke: #50D616;
            stroke-width: 1px;
            cursor: pointer;
        }
        .njg-node:hover,
        .njg-node.njg-open{
            fill: #ffee00;
        }

        .njg-link {
            stroke: black;
            stroke-width: 2;
            stroke-opacity: .8;
            cursor: pointer;
        }
        .njg-link:hover,
        .njg-link.njg-open{
            stroke-width: 3;
            stroke-opacity: 1
        }

        .njg-tooltip {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 5px 10px;
            border-radius: 3px;
        }
    </style>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css"/>
</head>
<body>
<?php
session_start();
if(!isset($_SESSION["is_open"]))
{
	header("Location: index.php?arg=login");
}
if(isset($_GET)&&isset($_GET["arg"]))
{
	switch ($_GET["arg"]) {
		case "inserted":
			echo "<script>alert('Word inserted')</script>";
			break;
		
		default:
			# code...
			break;
	}
}
$content= file_get_contents("wordnet.json");

$fileContent=json_decode($content);
$list=[];

foreach ($fileContent as $key => $value) {
	array_push($list, "'".$key."'");


	
}

$jList="var wordList=[".implode(",", $list)."]";
echo "<script>".$jList."</script>";
?>

<div class="w3-card-4" style="width:50%;margin:auto;">
<form class="w3-container" method="post" action="insertion.php">

<label class="w3-label">New Word</label>
<input class="w3-input" type="text" name="word" placeholder="new word"/>
<br>
<div class="drop">
<label class="w3-label">POS</label>
<select id="POS" required="" name="pos">
<option value="" disabled selected>Select POS</option>
	<option>NN</option>
	<option>JJ</option>
	<option>VB</option>
</select>
</div>
<div class="drop">
<label class="w3-label">PARENT</label>
<select id="PARENT"  name="parent">
<option value="" disabled selected>Select PARENT</option>
</select>
</div>
<div class="drop">
<label class="w3-label">CHILD</label>
<select id="CHILD" name="child">
	<option disabled selected>Select CHILD</option>
</select>
</div>
  <br><br>
<button class="w3-button w3-green" type="submit" name="submit" value="submit" >insert</button>
<button class="w3-button w3-green" type="reset" name="reset" value="reset" >reset</button><br>
</form>

<button class="w3-button w3-red" type="reset" onclick="logout(event)" style="float:right">logout</button><br>
<br>
</div>
<script>
function logout(event)
{
window.location="logout.php";

}
function init()
{
	var parent=document.getElementById("PARENT");
	for(let elem of wordList)
	{
		let subEl=document.createElement("option");
		subEl.innerHTML=elem;
		parent.appendChild(subEl);
	}
	parent.addEventListener("click",function(event)
	{
		var parent=event.target;
		var sel=parent.options[parent.selectedIndex].text;
		if(sel!="Select PARENT")
		{
			let child=document.getElementById("CHILD");
			
			while (child.firstChild) {
    child.removeChild(child.firstChild);
}
			
			let subElem=document.createElement("option");
			subElem.innerHTML="Select CHILD";
			subElem.setAttribute("disabled",true);
			subElem.setAttribute("selected",true);
			child.appendChild(subElem);

			for(let elem of wordList)
			{	if(elem==sel)continue;
				let subElem=document.createElement("option");
				subElem.setAttribute("value",elem);
				subElem.innerHTML=elem;
				child.appendChild(subElem);
			}
		}
	});

}
init();

       
        
            let child=document.getElementById("CHILD");
            
            while (child.firstChild) {
    child.removeChild(child.firstChild);
}
            
            let subElem=document.createElement("option");
            subElem.innerHTML="Select CHILD";
            subElem.setAttribute("disabled",true);
            subElem.setAttribute("selected",true);
            child.appendChild(subElem);

            for(let elem of wordList)
            {   
                let subElem=document.createElement("option");
                subElem.setAttribute("value",elem);
                subElem.innerHTML=elem;
                child.appendChild(subElem);
            }
        

</script>
<hr>
<div id="network-graph"></div>
    <script src="lib/d3.min.js"></script>
    <script src="src/netjsongraph.js"></script>
    <script>
	d3.behavior.zoom().scale(10);	
        d3.netJsonGraph("netgraph.json", {
            el: "#network-graph",
            defaultStyle:false,
            
        });
    </script>
</body>
</html>