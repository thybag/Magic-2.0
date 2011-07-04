<?php 
/**
 * Shows a list of categorys and sort directions for user to choose.
 */
//List of categoerys
$cat = array(0 => 'All', 1 => 'Helpful', 2 =>'Games', 3 => 'Novelty', 4 => 'Lifestyle');
?><div class='dash'>
		<ul>
		<?php
		//for each categoery, show a link.
		foreach($cat AS $idx => $category){
			//If we are viewing this categoery right now, add selected class to this
			//link.
			$selected = '';
			if(strtolower($category) == strtolower($this->path['method'])){
				$selected = 'selected';
			}
			//output link html.
			$href = Util::parsePath('appstore/'.$category);
			echo "<li><a href='{$href}' class='{$selected}'>{$category}</a></li>";
		}
		 ?>
		 </ul>
		 
		 <ul style='float:right;'>
			<li><a href='<?php echo Util::parsePath('appstore/popular'); ?>' <?php if(strtolower($this->path['method'])=='popular'){echo "class='selected'";} ?>>Popular</a></li>
			<li><a href='<?php echo Util::parsePath('appstore/newist'); ?>'  <?php if(strtolower($this->path['method'])=='newist'){echo "class='selected'";} ?>>New</a></li>
		 </ul>
	</div>