<?php
if(isset($_POST)&&isset($_POST["submit"]))
{
	$word=$_POST["word"];
	$pos=$_POST["pos"];
	if(isset($_POST["parent"]))
	$parent=$_POST["parent"];	
	else $parent=null;
	if(isset($_POST["child"]))
	$child=$_POST["child"];
	else $child=null;

	if(insert($word,$pos,$parent,$child))
	{
header("Location: insertword.php?arg=inserted");
	}
	else
	{
header("Location: insertword.php?arg=duplicate");
	}

}

function insert($word,$pos,$parent,$child)
{
$content= file_get_contents("wordnet.json");
$fileContent=json_decode($content);
$arg="inserted";
if(isset($fileContent->{$word}))
{return false;
}
else{
$content2= file_get_contents("netgraph.json");
$fileContent2=json_decode($content2);

	$parentList=[];
	$childList=[];

		if($parent!=null)
			array_push($parentList, $parent);
		if($child!=null)
			array_push($childList,$child);

		$previousChild=null;

		/*
		 * checking is the parent already have the child with the same pos tag
		 */
		for ($i = 0; $i < sizeof($parentList); $i++) {
				
			$chill=$fileContent->{$parentList[$i]}->children;

			
			for ($j = 0; $j < sizeof($chill); $j++) {
				if ($fileContent->{$chill[$j]}->pos==$pos) {
					$previousChild = $chill[$j];
					echo "yo";
					break 2;
				}
			}
		}
		$jsonObject=array("pos"=>$pos,"parents"=>$parentList);
		$jsonObject2=array("id"=>$word,"label"=>$word,"properties"=>array("POS"=>$pos));

		
		if ($previousChild == null) {
			/* if parent doesn't have child with same pos */
			/* simply make the new word child of given parents */

			//array_push($jsonObject, "children"=>$childList);
			$jsonObject["children"]=$childList;

			if ($parent != null)
				{array_push($fileContent->{$parent}->children,$word);
				array_push($fileContent2->links,array("source"=>$parent,"target"=>$word));
				}
			if ($child != null)
				{array_push($fileContent->{$child}->parents,$word);
				array_push($fileContent2->links,array("source"=>$word,"target"=>$child));
				}


		} else {
			/* if parent has child with same pos */

			/*
			 * replacing the current child of parent with this word. making the
			 * current child of parent, the child of this word.
			 */
			$fileContent->{$parent}->children=array_diff($fileContent->{$parent}->children, [$previousChild]);
			
			$tt=0;

			foreach($fileContent2->links as $elem)
			{
				if($elem->source==$parent&&$elem->target==$previousChild)
				{
					unset($fileContent2->links[$tt]);
					array_splice($fileContent2->links, $tt,1);
				}
				$tt++;
			}


			array_push($fileContent->{$parent}->children,$word);
			array_push($fileContent2->links, array("source"=>$parent,"target"=>$word));
			

		$fileContent->{$previousChild}->parents=array_diff($fileContent->{$previousChild}->parents, [$parent]);

			array_push($fileContent->{$previousChild}->parents,$word);
			

			if ($child != null) {
				$pos1 = $fileContent->{$child}->pos;
				$pos2 =$fileContent->{$previousChild}->pos;
				/*
				 * if the child of this word and current child of given parent
				 * have same pos then only one link is maintained
				 */
				if ($pos1==$pos2)
					{
						unset($childList);
						$childList=array();
					}
				else
				array_push($fileContent->{$child}->parents,$word);
				
			
			}
			array_push($childList, $previousChild);
			
			// array_push($jsonObject,"children"=>$childList);
			$jsonObject["children"]=$childList;

			for($i=0;$i<sizeof($childList);$i++)
				array_push($fileContent2->links,array("source"=>$word,"target"=>$childList[$i]));

			

		}
		/* insert the new word into the wordnet */
		$fileContent->{$word}=$jsonObject;
		array_push($fileContent2->nodes,$jsonObject2);

		$toFileContent=json_encode($fileContent,JSON_PRETTY_PRINT);
		$toFileContent2=json_encode($fileContent2,JSON_PRETTY_PRINT);

		$file=fopen("wordnet.json", "w");
		fwrite($file, $toFileContent);
		
		
		$file=fopen("netgraph.json", "w");
		fwrite($file, $toFileContent2);
		
return true;		
}

}

?>