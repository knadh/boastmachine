<?php if(defined('BLOG') && BLOG) { ?>

<strong><?php echo $lang['blog']; ?></strong><br />

	<a href="?action=edit_blog&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_blog_mod']; ?></a>
	<a href="?action=new_post&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_new']; ?></a>
    <a href="?action=list_posts&blog=<?php echo BLOG; ?>"><?php echo $lang['post_edit_title']; ?></a>
    <a href="?action=cats&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_cat']; ?></a>
    <a href="?action=search&blog=<?php echo BLOG; ?>"><?php echo $lang['search']; ?></a>
	<br />
<?php } ?>

<strong><?php echo $lang['admin_author']; ?></strong><br />
	<a href="?action=list_users&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_user_list']; ?></a>
	<a href="?action=mail_users&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_user_mail']; ?></a>
    <a href="?action=search&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_user_search']; ?></a>
    <a href="?action=add_user&blog=<?php echo BLOG; ?>"><?php echo $lang['admin_user_add']; ?></a>
	<br />

<strong><?php echo $lang['admin_system']; ?></strong><br />

	<a href="?action=settings"><?php echo $lang['admin_set']; ?></a>
	<a href="?action=file_manager"><?php echo $lang['admin_file']; ?></a>
    <a href="?action=themes"><?php echo $lang['admin_theme']; ?></a>
    <a href="?action=lang"><?php echo $lang['admin_lang']; ?></a>
    <a href="?action=backup"><?php echo $lang['admin_backup_rstr']; ?></a>
    <a href="?action=ban"><?php echo $lang['admin_block']; ?></a>
    <a href="?action=spam"><?php echo $lang['admin_spam']; ?></a>
    <a href="?action=refs"><?php echo $lang['admin_ref']; ?></a>
    <a href="?action=word"><?php echo $lang['admin_word']; ?></a>
    <a href="?action=mail_logs"><?php echo $lang['admin_mail_logs']; ?></a>
    <a href="?action=theme_editor"><?php echo $lang['admin_theme_editor']; ?></a>
    <a href="?action=link_manager"><?php echo $lang['admin_link_manager']; ?></a>
	<br />