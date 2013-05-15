<?php

/* =============================

	boastMachine language pack


	Language : English

	Orinial English language pack
	by Kailash Nadh

	kailash@boastology.com
	http://www.kailashnadh.name


	boastMachine v3.1 (BETA)
	http://boastology.com
	License: GPL

   ============================= */


// ******** EDIT THESE ********** //

$lang['name'] = "English Lang pack"; // Name of this pack
$lang['ENCODING'] = "utf-8"; // Encoding



// ******** DONOT TOUCH THIS !! ********** //
if(isset($load_lang_pack) && $load_lang_pack==true) { return; }




// ******** START TRANSLATION HERE ********** //

// Short terms

$lang['home']="Home";
$lang['total_articles']="Posts";
$lang['blog']="Blog";
$lang['blogs']="Blogs";
$lang['title']="Title";
$lang['summary']="Summary";
$lang['body']="Body";
$lang['author']="Author";
$lang['trackbacks']="Trackbacks";
$lang['calendar']="Calendar";
$lang['rating']="Rating";
$lang['rate']="Rate";
$lang['votes']="Votes";
$lang['cat']="Category";
$lang['cats']="Categories";
$lang['blog_roll']="Blog Roll";
$lang['att_file']="Attached Files";
$lang['send']="Send this";
$lang['syndicate']="Syndication";
$lang['comments']="Comments";
	// %num% is where the number of comments appear

$lang['archive']="Archives";
$lang['search']="Search";
$lang['show']="Show";
$lang['adv_search']="Advanced search";
$lang['search_this']="Search this site";
$lang['recent_posts']="Recent posts";
$lang['trackback_msg']="The trackback uri for this entry is";
$lang['tracked_on']="Tracked on";
$lang['trackback_list_title']="Listed below are the weblogs that reference this post";
$lang['links']="Links";
$lang['post_clear_but']="Clear";

$lang['hidden']="Hidden";
$lang['open']="Open";
$lang['draft']="Draft";

// Common strings
$lang['str_in']="in";
$lang['str_by']="by";
$lang['str_on']="on";
$lang['str_all']="All";
$lang['posted_in']="Posted in";
$lang['posted_by']="Posted by";
$lang['posted_on']="Posted on";
$lang['str_more']="more";	// (3.1)

$lang['close']="Close";
$lang['back']="Back";
$lang['page']="Page";

$lang['send_post']="Mail this";
$lang['send_post_title']="Mail this post to a friend";
$lang['post_comment']="Post a Comment";
$lang['view_comments']="View/Add comments";
$lang['print']="Printer friendly";
$lang['rate_this']="Rate the post";

// Printer friendly page

$lang['print_from']="Post from";
$lang['printed_from']="Printed from";

$lang['no_articles']="No posts were found in the database!";


$lang['powered']="Powered by boastMachine ".BMC_VERSION;

// =======================
// New post page

$lang['post_title']="Post title";
$lang['post_cat']="Category";
$lang['post_keys']="Keywords   [For search engine support]";
$lang['post_format']="HTML / Plain Text";

$lang['post_attach']="File Attachments";
$lang['post_attach_clear']="Clear Attachments";
$lang['post_attach_mg']="Manage Attachments";

$lang['post_note']="( The post body is optional. If you leave it empty, it will automatically be substitued with the post summary)";
$lang['post_smr']="Post Summary";
$lang['post_content']="Post body expanded (optional)";
$lang['post_content_note']=" [HTML or Plain text as selected] ";
$lang['post_html_toolbar']="HTML (WYSIWYG) editor"; // (3.1)
$lang['post_html_toolbar_no']="You must select HTML in the post Format to use the HTML toolbar!"; // (3.1)
$lang['post_html_cancel']="Cancel"; // (3.1)

$lang['post_normal']="Normal post";
$lang['post_date']="Post date (Enter EXACTLY in the format MM/DD/YY hh:mm:ss AMPM )";
$lang['post_draft']="Draft ? (Future post : set a date)";
$lang['post_hidden']="Hidden post?";
$lang['post_track']="Accept Pings? (trackbacks)";

$lang['post_no_blog']="The requested blog was not found";
$lang['post_no_other']="This blog is closed to posting";
$lang['post_no_associate']="You cannot post in a blog that you are not associated to"; // (3.1)
$lang['post_no_cat']="Unable to find the category you selected in the blog";
$lang['post_no_mod']="You are not allowed to post/edit this entry";

$lang['post_attached']="Attached files";
$lang['post_protected']="Password protected post? (Enter a password to make private)";
$lang['post_autobr']="AutoConvert line breaks to &lt;br /&gt;";
$lang['post_smile']="Smilies Info";
$lang['post_track_urls']="Trackback urls";
$lang['post_track_urls_info']="Enter trackback urls of external posts to send trackback pings";
$lang['post_post_but']="  Post  ";
$lang['post_post_preview']="Preview"; // (3.1)

$lang['post_edit_title']="Edit post";
$lang['post_edit']="Edit: \"%title%\"";
$lang['post_edit_no']="Unable to retrive data of the post!";
$lang['post_rss_no']="The post was successfully added, however the rss/xml feed generator failed. Please check the permission of the /rss directory";

// =======================
// Send to friend form

$lang['snd_name']="Your name";
$lang['snd_email']="Your Email";

$lang['snd_title']="Send the post \"%title%\" to a friend";
// Donot remove the %title% . The article's title appears there

$lang['snd_email_req']="Enter atleast 1 email!";
$lang['snd_em1']="Friend's email #1";
$lang['snd_em2']="Friend's email #2";
$lang['snd_em3']="Friend's email #3";
$lang['snd_em4']="Friend's email #4";
$lang['snd_em5']="Friend's email #5";
$lang['snd_comments']="Your comments [Optional]";
$lang['snd_but']=" SEND ";

$lang['snd_no']="Sending posts is disabled!";
$lang['snd_inv_email']="Invalid EMAIL address!";
$lang['snd_inv_email_msg']="You have entered an invalid email in the 'email' field!";
$lang['snd_inv_email_to_msg']="You have entered an invalid email in the 'to' field!";

$lang['snd_success']="Thank You!<br />The post \"%article%\" was sent successfully to the following recipients";


// =======================
// Search

$lang['search_in']="Search in";
$lang['search_title']="Title";
$lang['search_content']="Content";
$lang['search_reslut_msg']="Search results for %key%";
$lang['search_resut_no']="No results were found matching your keyword!";
$lang['search_resut_no_key']="Keywords too short! Please use atleast 3 characters!";


// =======================
// USERS

// Registration and user info

$lang['users']="Users";
$lang['user_box_txt']="Members";
$lang['user_box_acc']="My account";
$lang['user_reg_title']="New user registration";
$lang['user_login']="Username";
$lang['user_name']="Full name";
$lang['user_email']="Email";
$lang['user_url']="Homepage";
$lang['user_pass']="Password";
$lang['user_nick']="Nick Name";
$lang['user_location']="Location";
$lang['user_birth']="Birth date";
$lang['user_yim']="Yahoo IM";
$lang['user_msn']="MSN IM";
$lang['user_icq']="ICQ";
$lang['user_profile']="Profile";
$lang['user_profile_total_posts']="Total posts";
$lang['user_profile_total_cmts']="Total comments";
$lang['user_profile_last_login']="Last login";
$lang['user_reg_but']="Register";
$lang['user_signup']="Signup";

$lang['user_blogs']="Associated blogs";
$lang['user_blogs_no_assoc']="You cant associate yourself to frozen blogs!";
$lang['user_short_user']="Username too short! Must be atleast 3 characters in length";
$lang['user_short_pass']="Password too short! Must be atleast 5 characters in length";
$lang['user_pass_nomatch']="Passwords donot match!";
$lang['user_pic']="Profile pic";
$lang['user_pic_show']="Display profile pic?";
$lang['user_pic_size_fail']="Sorry! The maximum allowed file size for the profile picture is %size% KB!";
$lang['user_pic_dimension_fail']="Sorry! The image should be less than %width% x %height% pixels!";
$lang['user_exists_msg']="The username or email you chose is already registered!";
$lang['user_blog_no']="There are no open blogs to which you can associate yourself! Please try signing up later!";

$lang['user_reg_success_title']="Registration successful!";
$lang['user_reg_success_msg']="Thank you for registering! Your account has been successfully setup and your account info has been mailed to your email";

$lang['user_welcome_subject']="Thank you for joining!"; // New user welcome email subject
$lang['user_notify_subject']="Notification: New user registration!"; // Subject of the notification mail you get when a new user registers
$lang['user_forgot_subject']="Forgot password request"; // Subject of the forgot password mail

$lang['user_no_accept']="Sorry! The administrator has chosen not to accept any user registrations at the moment. Please check back later.";



$lang['user_joined']="Date joined";
$lang['user_disp_id']="Display ID";
$lang['user_disp_email']="Display email publicly ?";
$lang['user_disp_profile']="Public Profile ?";

// Login
$lang['user_login_title']="User login";
$lang['user_logout']="Logout";
$lang['user_login_but']="Login";
$lang['user_login_remember']="Remember me?";
$lang['user_new']="New user";
$lang['user_login_false']="Invalid username or password!";
$lang['user_forgot_pass']="Forgot Password";
$lang['user_forgot_but']="Get my password";
$lang['user_forgot_false']="No user account was found with that email id!";
$lang['user_frozen']="You have been suspended by the administrator!";
$lang['user_forgot_send_msg']="Your password has been reset and has been sent to your email!";

// Panel
$lang['user_mbr_title']="Members area";
$lang['user_welcome']="Welcome %name% !";
$lang['user_last_login']="Your last login was on %login%";

$lang['user_level_info']="Your level is '%level%' and your rights are : ";
$lang['user_level_0']="Frozen";
$lang['user_level_1']="Commenting on posts";
$lang['user_level_2']="New posts / Commenting";
$lang['user_level_3']="Add/Edit/Delete Posts / Commenting";
$lang['user_level_4']="Administrator ( Full privilages )";

$lang['user_post_new']="New post";
$lang['user_post_edit']="Edit posts";
$lang['user_acc']="My account";

$lang['user_admin']="ADMIN";

$lang['user_new_title']="Write a new post";
$lang['user_pass_need']="Only need to be entered if new";


// File manager
$lang['file_title']="Upload files";
$lang['file_fl']="File ";
$lang['file_but']=" UPLOAD ";
$lang['file_but_del']="Delete selected";
$lang['file_but_run']="Run / Download"; // (3.1)
$lang['file_del_msg']="Are you sure want to delete the selected files?";
$lang['file_add_but']="Attach Files";

$lang['file_img_resize']="Resize to thumbnail ?"; // (3.1)
$lang['file_img_insert']="Insert to post"; // (3.1)
$lang['file_img_insert_target']="Insert to :"; // (3.1)
$lang['file_img_insert_body']="Post body"; // (3.1)


$lang['file_fail']="Upload %num% failed!";
$lang['file_fail_size']="Invalid or Excess file size for file %num%";
$lang['file_fail_ext']="Invalid or restricted file extension for file %num%";
$lang['file_done']="File %num% uploaded successfully!";



// =======================
// Date / Calendar (3.1)

$lang['caln_tip']="Posts made on";
$lang['caln_next']="Next month";
$lang['caln_prev']="Previous month";

// Full Weekdays
$lang['date']['Sunday'] = 'Sunday';
$lang['date']['Monday'] = 'Monday';
$lang['date']['Tuesday'] = 'Tuesday';
$lang['date']['Wednesday'] = 'Wednesday';
$lang['date']['Thursday'] = 'Thursday';
$lang['date']['Friday'] = 'Friday';
$lang['date']['Saturday'] = 'Saturday';

// Weekdays Abbrivated
$lang['date']['Sun'] = 'Sun';
$lang['date']['Mon'] = 'Mon';
$lang['date']['Tue'] = 'Tue';
$lang['date']['Wed'] = 'Wed';
$lang['date']['Thu'] = 'Thu';
$lang['date']['Fri'] = 'Fri';
$lang['date']['Sat'] = 'Sat';

// Weekdays even shorter :)
$lang['date']['week_day_1'] = 'S';
$lang['date']['week_day_2'] = 'M';
$lang['date']['week_day_3'] = 'T';
$lang['date']['week_day_4'] = 'W';
$lang['date']['week_day_5'] = 'T';
$lang['date']['week_day_6'] = 'F';
$lang['date']['week_day_7'] = 'S';

// Months
$lang['date']['January'] = 'January';
$lang['date']['February'] = 'February';
$lang['date']['March'] = 'March';
$lang['date']['April'] = 'April';
$lang['date']['May'] = 'May';
$lang['date']['June'] = 'June';
$lang['date']['July'] = 'July';
$lang['date']['August'] = 'August';
$lang['date']['September'] = 'September';
$lang['date']['October'] = 'October';
$lang['date']['November'] = 'November';
$lang['date']['December'] = 'December';

// Months abbrivated
$lang['date']['Jan'] = 'Jan';
$lang['date']['Feb'] = 'Feb';
$lang['date']['Mar'] = 'Mar';
$lang['date']['Apr'] = 'Apr';
$lang['date']['May'] = 'May';
$lang['date']['Jun'] = 'Jun';
$lang['date']['Jul'] = 'Jul';
$lang['date']['Aug'] = 'Aug';
$lang['date']['Sep'] = 'Sep';
$lang['date']['Oct'] = 'Oct';
$lang['date']['Nov'] = 'Nov';
$lang['date']['Dec'] = 'Dec';


// =======================
// ADMIN

// General

$lang['admin']="Admin";
$lang['admin_panel']="Administration panel";
$lang['admin_not']="You are not a valid administrator! Only an authorized administrator can access the Admin area!";

$lang['admin_welcome']="Administration area";
$lang['admin_mode']="boastMachine %ver%";
$lang['admin_legend']="C - Number of comments &nbsp;&nbsp;,&nbsp; S - Status [ If checked, hidden/frozen ] &nbsp;&nbsp;,&nbsp; X - Delete";

$lang['admin_home']="Admin Home";
$lang['admin_system']="System";
$lang['admin_logout']="Logout";
$lang['admin_updates']="Check for Updates"; // (3.1)
$lang['admin_docs']="Documentation";
$lang['admin_new']="New Post";
$lang['admin_author']="Users";
$lang['admin_cat']="Categories";
$lang['admin_word']="Word Filter";
$lang['admin_delall']="Delete All";
$lang['admin_backup_rstr']="Backups";
$lang['admin_theme']="Themes";
$lang['admin_block']="Block IPs";
$lang['admin_spam']="SPAM Filter";	// (3.1)
$lang['admin_theme_editor']="Template editor";
$lang['admin_lang']="Languages";
$lang['admin_ref']="Referrers";
$lang['admin_set']="Settings";
$lang['admin_status']="Status";
$lang['admin_file']="File Manager";
$lang['admin_user_list']="List users";
$lang['admin_user_add']="Add user";
$lang['admin_user_mail']="Mail users";
$lang['admin_user_search']="Search users";
$lang['admin_users_online']="Users currently online"; // (3.1)

// Stats (3.1)
$lang['admin_stats_blogs']="Total blogs";
$lang['admin_stats_users']="Total users";
$lang['admin_stats_posts']="Total posts";
$lang['admin_stats_cmts']="Total comments";

// Blogs
$lang['admin_blog_title']="My Weblogs";
$lang['admin_blog_manage']="Manage blog";
$lang['admin_blog_date']="Created on ";
$lang['admin_blog_view']="View";
$lang['admin_blog_mod']="Modify";
$lang['admin_blog_del']="Delete weblog";
$lang['admin_blog_new']="Create weblog";
$lang['admin_blog_update']="Update weblog";
$lang['admin_blog_new_file']="Static filename";
$lang['admin_blog_new_file_help']="The name for static .php file which sould be created in the base directory\\nThe blog will be accessed by calling this static file\\nEg: giving my_weblog will create a file named my_weblog.php and this blog will be accessed from http://yoursite.com/blog/my_blog.php";
$lang['admin_blog_new_file_no']="The static .php file exists in the base directory! Please choose a different filename!";
$lang['admin_blog_new_name']="Blog name";
$lang['admin_blog_theme']="Blog theme";
$lang['admin_blog_info']="Blog description";
$lang['admin_blog_frozen']="Blog frozen?";
$lang['admin_blog_users']="Allow other users to post?";
$lang['admin_blog_new_err']="A blog with the same name exists!";
$lang['admin_blog_del_title']="Are you sure want to delete this blog?";
$lang['admin_blog_del_msg']="All the posts, comments, blog settings etc.. would be permanently lost!";
$lang['amin_blog_no']="No blog with that id was found!";
$lang['admin_blog_list_posts']="List entries";

// =======================
// Search users and Posts
$lang['admin_search_short']="Keywords too short!";
$lang['admin_search_no']="No results were found matching your criteria";
$lang['admin_search_posts_selblog']="blog where the searching is to be done";
$lang['admin_search_results']="%num% results found";
$lang['admin_search_users']="Search users";
$lang['admin_search_users_show']="Show users";
$lang['admin_search_posts']="Search posts";
$lang['admin_search_posts_show']="Show posts";
$lang['admin_search_user_regd']="and registered";

// Mail logs
$lang['admin_mail_logs']="Mail Logs";
$lang['admin_mail_logs_no']="No logs were found!";
$lang['admin_mail_logs_by']="Sent by %email% on ";
$lang['admin_mail_view']="View Logs";
$lang['admin_mail_clear']="Clear Logs";

$lang['admin_cmts']="Comments";

$lang['admin_stat_sel_but']="Change Status";
$lang['admin_del_sel_but']="Delete selected";

// Referer logging
$lang['admin_ref_time']="Time";
$lang['admin_ref_ip']="IP address";
$lang['admin_ref_url']="Referrer url";

// Link manager (3.1)
$lang['admin_link_manager']="Link Manager";
$lang['admin_link_links']="Links";
$lang['admin_link_add_to']="Add to";
$lang['admin_link_add_all']="All blogs";
$lang['admin_link_new']="New Link?";
$lang['admin_link_but_mod']="Add / Edit";
$lang['admin_link_but_del']="Delete";
$lang['admin_link_title']="Link Title";
$lang['admin_link_url']="Link URL";
$lang['admin_link_desc']="Link Description";


// Theme editor
$lang['admin_editor_dir']="Theme directories";
$lang['admin_editor_files']="File list";
$lang['admin_editor_path']="Path";
$lang['admin_editor_sel_dir']="Select a directory";
$lang['admin_editor_sel_file']="Select a file";
$lang['admin_editor_error']="Could not save the edited file! Please check the file permission!";

// Word filter
$lang['admin_bad_title']="Word Filter";
$lang['admin_bad_words']="Enter words to be filtered, one per line";
$lang['admin_file_no']="File uploading is not permitted!";
$lang['admin_bad_but']="Save";


// Posts
$lang['admin_post_title']="Post title";
$lang['admin_post_date']="Date";
$lang['admin_post_author']="Author";
$lang['admin_post_act']="Action";
$lang['admin_post_edit']="Edit";
$lang['admin_post_view']="View post";
$lang['admin_post_cmt']="Comments";
$lang['admin_post_no']="No posts were found in this blog!";

$lang['admin_post_search']="Search posts";
$lang['admin_post_show']="Show posts from";
$lang['admin_post_week']="This week";
$lang['admin_post_month']="This month";
$lang['admin_post_last_month']="Last month";
$lang['admin_post_last_6month']="Last 6 months";
$lang['admin_post_last_year']="Last one year";
$lang['admin_del_chk']="Select all - Delete posts";
$lang['admin_status_chk']="Select all - Hide/Unhide posts";

$lang['admin_del_all_post']="WARNING! Are you sure want to delete all Posts?";
$lang['admin_del_post_msg']="Are you sure want to delete the selected post(s) ?";
$lang['admin_hide_post']="Hide/Unhide this post";
$lang['admin_edit_post']="Edit this Post";


// Categories
$lang['admin_cat_title']="%blog% :: Categories";
$lang['admin_cat_mod']="Add / Edit";
$lang['admin_cat_name']="Category name";
$lang['admin_cat_info']="Category description";
$lang['admin_cat_new']="New category?";
$lang['admin_cat_stats']="Category stats";
$lang['admin_cat_total']="Total posts in %cat%";
$lang['admin_cant_manage']="Category management";
$lang['admin_cat_least']="There must be atleast one category in the blog!";
$lang['admin_cat_msg']="Are you sure want to delete this category and all its posts?";
$lang['admin_cat_exist']="A category with the same name already exists!";

$lang['admin_but_add']="Add";
$lang['admin_but_edit']="Edit";
$lang['admin_but_del']="Delete";


// Mail all users
$lang['admin_mail_subj']="Mail subject";
$lang['admin_mail_level']="With level";
$lang['admin_mail_msg']="Message";
$lang['admin_mail_send']="Send message";
$lang['admin_mail_keywords']="The following quick tags can be used in the message";
$lang['admin_mail_key_login']="User's login name";
$lang['admin_mail_key_name']="User's full name";
$lang['admin_mail_key_nick']="User's nick name";
$lang['admin_mail_key_url']="User's homepage";
$lang['admin_mail_key_email']="User's email";
$lang['admin_mail_key_level']="User's level";
$lang['admin_mail_success']="Successfully mailed %num% users!";


// IP blocking
$lang['admin_block_title']="Block/Unblock IPs";
$lang['admin_block_ip']="Enter IPs to be blocked 1 per line.<br />Enter a full IP or a partial one. <br />
eg: 202.155.26.33 or 202.144.";
$lang['admin_blck_but']="Save";

// SPAM filter (3.1)
$lang['admin_spam_title']="SPAM filter (comments/trackbacks)";
$lang['admin_spam_info']="Enter the spam-sensitive words (ONE PER LINE)<br />All comments/trackbacks containing any of these will be blocked and the user posting, will be Blocked";
$lang['admin_spam_ban']="Ban(ip) the Spammer?";
$lang['admin_spam_but']="Save";

// Backup and restore
$lang['admin_backup_title']="Backup/Restore data";
$lang['admin_backup_restore']="Restore data";
$lang['admin_backup_delete']="Delete";
$lang['admin_backup_previous']="Previous backups";
$lang['admin_backup_but']="Backup all Data";
$lang['admin_backup_gzip']="(Compress the backup using gzip?)";
$lang['admin_backup_note']="You will be able to upload the backup file and restore the data at any time";
$lang['admin_restore_title']="Upload a backup file and restore the data";
$lang['admin_restore_warn']="(WARNING! This will destroy existing data!)";
$lang['admin_restore_fail']="Upload of backup file failed! Please check the /backup directory's permission !";
$lang['admin_restore_but']="Upload and Restore";
$lang['admin_restore_fail']="Database restore failed!";
$lang['admin_restore_ok']="Database restored successfully!";

// Themes
$lang['admin_theme_title']="Theme management";
$lang['admin_theme_current']="Current Theme";
$lang['admin_theme_info']="( This is going to be the global theme applicable for all blogs. This wont affect their individual theme setting of the blogs )";
$lang['admin_theme_apply_but']="Apply theme";
$lang['admin_theme_del_but']="Delete theme";
$lang['admin_theme_del_msg']="Are you sure want to permanently remove the selected theme from the server?";
$lang['admin_theme_del_no']="Sorry! You cant delete the theme that you are currently using!";

// Language packs
$lang['admin_lang_title']="Language Packs";
$lang['admin_lang_current']="Current Pack";
$lang['admin_lang_but']="Select Language";
$lang['admin_lang_up']="Upload a language pack";
$lang['admin_lang_up_ow']="Overwrite if exists?";
$lang['admin_lang_up_ow_no']="The language pack was not uploaded as a file with the same name exists!";
$lang['admin_lang_up_no']="File upload failed! Please check the /inc/lang/ directory permission!";
$lang['admin_lang_bad']="Corrupt or bad language pack! Please ensure that you are uploading a valid lang file!";
$lang['admin_lang_up_but']="Upload";
$lang['admin_lang_del_but']="Delete language pack";
$lang['admin_lang_del_msg']="Are you sure want to completely delete this language pack?";
$lang['admin_lang_del_no']="You cannot delete the lang pack currently in use!";

// Users
$lang['admin_user_list']="User list";
$lang['admin_user_level']="Level";
$lang['admin_user_total']="Total users";
$lang['admin_user_admin_total']="Total admins";
$lang['admin_user_suspended']="Suspended users";
$lang['admin_user_save_bt']="Save changes";
$lang['admin_user_edit']="Edit user";
$lang['admin_user_del']="Delete user";
$lang['admin_user_del_msg']="Warning! Are you sure want to permanently delete this user and all the posts and comments made by this user?";
$lang['admin_user_exist']="The user already exists!";
$lang['admin_user_atleast']="There must be atleast 1 user in the system!";
$lang['admin_user_nodel']="You cant delete the super admin!";
$lang['admin_user_del_msg']="Are you sure want to delete the author and all his posts?";
$lang['admin_user_add']="Add User";
$lang['admin_user_add_bt']=" ADD ";
$lang['admin_user_no']="User with that ID doesn't exist!";

// Settings
$lang['admin_sett_title']="boastMachine settings";
$lang['admin_sett_site_sett']="Site Settings";
$lang['admin_sett_aemail']="From email";
$lang['admin_sett_burl']="boastMachine URL";
$lang['admin_sett_site_title']="Blog site title";
$lang['admin_sett_desc']="Site description";
$lang['admin_sett_datestr']="Date String";
$lang['admin_sett_gmtdiff']="Difference in hours from GMT"; // (3.1)
$lang['admin_sett_mail_subj']="\"Send to Friend\" mail subject";

$lang['admin_sett_system_sett']="System settings";

$lang['admin_sett_users']="Accept user registrations?";
$lang['admin_sett_new_welcome']="Send new users a welcome mail?";
$lang['admin_sett_default_level']="Default user level?";
$lang['admin_sett_new_notify']="Notify you when a new user signs up?";
$lang['admin_sett_archive']="Archiving?";
$lang['admin_sett_cmt']="Enable commenting?";
$lang['admin_sett_cmt_guests']="Allow unregistered users to comment?";
$lang['admin_sett_cmtsess']="One comment per session?";
$lang['admin_sett_cmt_thread']="Enable threaded comments?";	// (3.1)
$lang['admin_sett_cmt_notify']="Enable comment notification?";	// (3.1)
$lang['admin_sett_cmt_verify']="Enable Image verification for comments?"; // (3.1)
$lang['admin_sett_vote']="Enable Voting/Rating?";
$lang['admin_sett_send']="Allow sending posts?";
$lang['admin_sett_search']="Allow searching?";
$lang['admin_sett_xml']="Enable RSS(XML) syndication?";

$lang['admin_sett_misc_sett']="Misc settings";
$lang['admin_sett_ping']="Send pings?";
$lang['admin_sett_ping_urls']="Hosts to be pinged (XML-RPC method)";
$lang['admin_sett_pass_note']="(Leave blank if not new)";

$lang['admin_sett_total']="Total posts to be shown";
$lang['admin_sett_ppage']="Posts per page";
$lang['admin_sett_titlewrap']="Title wrap length";
$lang['admin_sett_smrwrap']="Summary wrap length";
$lang['admin_sett_autlink']="Enable AutoLinks?";
$lang['admin_sett_html']="Enable HTML posts?";
$lang['admin_sett_files']="Allow file uploads?";
$lang['admin_sett_trackbacks']="Allow TrackBacks"; // (3.1)


$lang['admin_sett_chk_yes']=" Yes ";
$lang['admin_sett_chk_no']=" No ";
$lang['admin_sett_save_but']=" SAVE SETTINGS "; // (3.1)



// Settings tool tips

$lang['admin_sett_tip_email']="\'From\' email address on mails sent out";
$lang['admin_sett_tip_burl']="URL to your boastMachine installation \( NO SLASH AT THE END! \)";
$lang['admin_sett_tip_site_title']="Title of your boastMachine blog site";
$lang['admin_sett_tip_desc']="Description of your blog site";
$lang['admin_sett_tip_datestr']="String to format your date display";

$lang['admin_sett_tip_tzone']="Your TimeZone"; // (3.1)
$lang['admin_sett_tip_timezone']="Your TimeZone"; // (3.1)
$lang['admin_sett_tip_gmtdiff']="The difference between your time and the GMT in hours\\nEither select from the above list or enter manually\\neg: -5.5 , 3 , -2"; // (3.1)

$lang['admin_sett_tip_sendm']="Subject of the mail sent from the \'send to friend\' form";
$lang['admin_sett_tip_ppage']="Posts to be shown per page";
$lang['admin_sett_tip_total']="Total posts to be displayed. Rest of the posts will be archived";
$lang['admin_sett_tip_twrap']="Number of characters where the Title has to be wrapped (Formatting)";
$lang['admin_sett_tip_swrap']="Number of characters where the Summary is to be wrapped\\nLeave 0 to do no wrapping"; // (3.1)
$lang['admin_sett_tip_users']="Allow new users to register themselves so that they can post/comment on your blogs";

$lang['admin_sett_tip_new_welcome']="Send the new users a welcome mail with their account info?";
$lang['admin_sett_tip_new_notify']="Notify you with an email when someone signs up?";
$lang['admin_sett_tip_default_level']="The default level to be assigned to a user when he signs up";

$lang['admin_sett_tip_archive']="Enable monthly Archival?";
$lang['admin_sett_tip_cmt']="Allow users to comment on your posts?";
$lang['admin_sett_tip_cmt_guests']="Allow guest users who is not registered, to post comments on posts?";
$lang['admin_sett_tip_cmt_sess']="Allow only one comment per user / session? (Prevents flooding)";
$lang['admin_sett_tip_cmt_thread']="Comment threading enables users to reply to a comment posted by someone"; // (3.1)
$lang['admin_sett_tip_cmt_notify']="Notifies the author via email when someone comments on his/her post";	// (3.1)
$lang['admin_sett_tip_cmt_verify']="Users will have to manually enter a code displayed in an image.\\nHelps prevent comment spamming"; // (3.1)

$lang['admin_sett_tip_vote']="Enable Rating/Voting posts?";
$lang['admin_sett_tip_send']="Allow sending posts to others? (Referring)";
$lang['admin_sett_tip_search']="Allow users to search posts?";
$lang['admin_sett_tip_xml']="Enable publishing of XML feeds so that search engines and content aggregators can index your blog feeds";
$lang['admin_sett_tip_autolink']="Enable autolinking (Auto convert urls to clickable links in posts/comments)";
$lang['admin_sett_tip_html']="Allow users to post in HTML format?";
$lang['admin_sett_tip_files']="Allow users to upload files and attach them with posts?";
$lang['admin_sett_tip_trackbacks']="Turn this off to STOP accepting tracks on your posts from other sites."; // (3.1)
$lang['admin_sett_tip_ping']="The sites to be pinged each time a new post is added to your blogs. \\n (Adds your blog to the recent blog list, eg: weblogs.com)";
$lang['admin_sett_tip_ping_urls']="Enter the hosts to be pinged. One url per line";


// =======================
// Comments page

$lang['del_cmts_del_but']=" Delete All Comments ";
$lang['del_cmt_msg']="Are you sure want to delete all comments for this post?";

$lang['del_cmt_one']="Are you sure want to delete this comment?";

$lang['cmt_no_one']="No comments have been made on this post yet!";
$lang['del_cmts_save_but']=" Save Changes ";

$lang['cmt_posted_by']="Posted by";
$lang['cmt_posted_date']="on date";


$lang['cmt_post_ttl']="Post a new comment";
$lang['cmt_empty_back']="Please go back and correct";
$lang['cmt_empty_field']="Empty %field% ! Please go back and correct!";
$lang['cmt_no_comment']="Sorry! Commenting is disabled!";
$lang['cmt_no_comment_post']="Commenting on this post is disabled!";
$lang['del_cmt_sess']="You can post only one comment per session on a post!";

// User comment posting page

$lang['cmt_name']="Name";
$lang['cmt_email']="Email";
$lang['cmt_url']="URL";
$lang['cmt_comment']="Comments";
$lang['cmt_guest_no']="You need to be logged in to post comments. Please login <a href=\"login.php\">here</a>";
$lang['cmt_thread_reply']="Reply to this comment"; // (3.1)
$lang['cmt_thread_reply_id']="Reply to comment id"; // (3.1)
$lang['cmt_notfiy']="Notify me when someone replies"; // (3.1)
$lang['cmt_notfiy_subject']="New comment notification mail"; // (3.1)
$lang['cmt_verify']="Verification code"; // (3.1)
$lang['cmt_verify_wrong']="Sorry! You entered a WRONG verification code!"; // (3.1)
$lang['cmt_submit_but']=" Post ";


// =======================
// General Error messages

$lang['error']="Error!";
$lang['post_pass_invalid']="Oops! You entered the wrong password!";
$lang['empty_fields']="Oops! You missed some form fields! Please recheck.";
$lang['denied']="ACCESS DENIED!";
$lang['err_write']="Cannot write to the directory";
$lang['no_file']="Unable to read the file %file%";

$lang['no_connect_boastology']="Cannot connect to boastology.com to retrieve updates";	// (3.1)
$lang['no_shout']="Shout box is disabled!";
$lang['no_id']="No post was found with that id !";
$lang['no_archive']="No Posts were found in the month %date%";
$lang['no_blog']="The blog you are trying to access does not exist!";
$lang['no_data']="No data available";
$lang['no_data_avail']="No posts currently available";
$lang['no_cat']="No category was found with the id %id%";
$lang['no_cat_posts']="No posts were found in the category %cat% !";
$lang['no_data_search']="There are no posts in the database to search";
$lang['no_logs']="No Logs available";
$lang['admin_clr_log_msg']="Unable to write to the log.txt file!<br />Please check whether the file exists and its permission is 777";
$lang['admin_log_write_msg']="Cannot write to the LOG file!";
$lang['banned']="You have been banned from this system!";
$lang['spammer']="You have been caught SPAMMING!";	// (3.1)
$lang['profiles_invalid']="No user with that ID was found";
$lang['profiles_no_pub']="The user has chosen not to display his profile";
$lang['post_frozen']="The post which you were trying to access has been frozen by the Administrator";
$lang['blog_frozen']="The blog has been frozen by the Administrator";
$lang['user_frozen']="The user has been frozen by the Administrator";
$lang['rss_no_blog']="Cannot generate RSS feed! The blog you requested doesn't exist!";


// Thank you for contributing this language pack to the boastMachine community

?>