<?php $title = 'BLO - Sheet Music Archive'; ?>
<?php include('header.php'); ?>
<h2>Sheet Music Archive</h2>
<p>These are songs that are no longer in our song rotation, but feel free to put them into yours!</p>

<?php
$dir = "./sheetmusic_archive/";
include('generate_music_index.php'); 
?>


<?php include('footer.php'); ?>
