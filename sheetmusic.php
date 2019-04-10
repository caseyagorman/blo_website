<?php $title = 'BLO - Sheet Music'; ?>
<?php include('header.php'); ?>
<h2>Sheet Music</h2>
<p>We have posted sheet music for the arrangements of the songs we play as PDF files. We have also included scores below as .ly files for editing with <a href="http://lilypond.org">LilyPond</a> or .MUS files for editing with Finale, so you can make your own arrangements. Please feel free to download and use these arrangements, or to email us with suggestions, corrections, and additional music.</p>
<p>We also have an <a href='sheetmusic_archive.php'>archive of music for songs we don't play anymore</a>, and <a href='sheetmusic_working.php'>here's the tunes we're currently trying out</a></p>	

<?php
$dir = "./sheetmusic/";
include('generate_music_index.php'); 
?>


<?php include('footer.php'); ?>
