<?php
/* Smarty version 3.1.33, created on 2018-12-03 21:38:47
  from 'C:\xampp\htdocs\osaisen\templates\parts\header.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5c059457d24db4_23056129',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1cdf92261b8a7184b70dfd48ec7e7ae616d1b9e1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\osaisen\\templates\\parts\\header.html',
      1 => 1543869506,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c059457d24db4_23056129 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<!--[if IE]>
    <?php echo '<script'; ?>
 src="http://html5shiv.googlecode.com/svn/trunk/html5.js"><?php echo '</script'; ?>
>
<![endif]-->
<link rel="stylesheet" type="text/css"
	href="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;?>
/css/common.css?<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
" />
<link rel="stylesheet" type="text/css"
	media="only screen and (min-width: 1024px)"
	href="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;?>
/css/pc_timeline.css?<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
" />
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['csss']->value, 'css');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['css']->value) {
?>
<link rel="stylesheet" type="text/css"
	href="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;
echo $_smarty_tpl->tpl_vars['css']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
" />
<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

<?php echo '<script'; ?>
 type="text/javascript"
	src="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;?>
/js/jquery.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript"
	src="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;?>
/js/common.js?<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript"
	src="<?php echo $_smarty_tpl->tpl_vars['page_context']->value;?>
/js/timeline.js?<?php echo $_smarty_tpl->tpl_vars['time']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
<?php echo '</script'; ?>
>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body><?php }
}
