<h1><?php echo $title_str; ?></h1>

<div id="form_fields">
<form name="contact" method="post" action="mail.php">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" name="blog" value="<?php echo $i_blog['id']; ?>" />
<input type="hidden" name="action" value="mail" />

<?php echo $lang['snd_name']; ?><br />
<input type="text" name="name" size="24" /><br />

<?php echo $lang['snd_email']; ?><br />
<input type="text" name="email" size="24" /><br /><br />

<?php echo $lang['snd_em1']; ?><br />
<input type="text" name="e1" size="24" /> (<?php echo $lang['snd_email_req']; ?>)<br />

<?php echo $lang['snd_em2']; ?><br />
<input type="text" name="e2" size="24" /><br />

<?php echo $lang['snd_em3']; ?><br />
<input type="text" name="e3" size="24" /><br />

<?php echo $lang['snd_em4']; ?><br />
<input type="text" name="e4" size="24" /><br />

<?php echo $lang['snd_em5']; ?><br />
<input type="text" name="e5" size="24" /><br /><br />

<?php echo $lang['snd_comments']; ?><br />
<textarea name="comments" rows="11" cols="50"></textarea><br />


<input type="submit" value="<?php echo $lang['snd_but']; ?>" />
</form>

</div> <!-- end form_fields //-->