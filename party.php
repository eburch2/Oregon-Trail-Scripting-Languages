<?php
include 'commonUI.php';
startHTML("Select Difficulty");
?>
	<br><img src="assets/separator.png"><br><br>
	<div id="div_options">
		What are the names of the people in your party?
		<form action="month.php" method="post">
			<?php if (isset($_POST['difficulty'])) { ?>
			<input type="hidden" name="difficulty" value="<?php echo $_POST['difficulty']; ?>" >
			<?php } ?>
			<input type="text" name="party_mem_1" placeholder="Joe"><br>
			<input type="text" name="party_mem_2" placeholder="Jill"><br>
			<input type="text" name="party_mem_3" placeholder="John"><br>
			<input type="text" name="party_mem_4" placeholder="Jackee"><br>
			<input type="text" name="party_mem_5" placeholder="Jerry"><br>
			<input type="submit" value="CLICK to continue">
		</form>
		<?php 
			if (isset($_POST['leader_name'])) {
				echo $_POST['leader_name']; 
			}

			if (isset($_POST['difficulty'])) {
				switch ($_POST['difficulty']) {
					case "1.":
						echo "Banker";
						break;
					case "2.":
						echo "Carpenter";
						break;
					case "3.":
						echo "Farmer";
						break;
					default:
						break;
				}
			}
		?>
	</div>
	<br><img src="assets/separator.png"><br>

<?php
endHTML();
?>