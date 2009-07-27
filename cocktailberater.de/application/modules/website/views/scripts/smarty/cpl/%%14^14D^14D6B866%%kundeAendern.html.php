<?php /* Smarty version 2.6.18, created on 2008-02-03 18:01:47
         compiled from kunde/kundeAendern.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'kunde/kundeAendern.html', 4, false),)), $this); ?>
<form action="" method="post" enctype="multipart/form-data"
	name="kundeAendernForm">
<div>
<div><?php echo smarty_function_html_image(array('file' => ($this->_tpl_vars['logo'])), $this);?>
</div>
<br>
<h2>Kunde &auml;ndern:</h2>
<div class="box"><strong>Vorname:</strong></div>
<div><?php print $this->_tpl_vars[felder][vorname] ?></div>
<div class="box"><strong>Nachname:</strong></div>
<div><?php print $this->_tpl_vars[felder][nachname] ?></div>
<?php $this->assign('feld', 'email'); ?>
<div class="box"><strong>E-Mail Adresse:</strong></div>
<div><input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php print $this->_tpl_vars[felder][email] ?>"></div>
<?php $this->assign('feld', 'passwort'); ?>
<div class="box"><strong>Passwort:</strong></div>
<div><input type="password" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
"></div>
<?php $this->assign('feld', 'geb_datum'); ?>
<div class="box"><strong>Geburtsdatum:</strong></div>
<div><?php print $this->_tpl_vars[felder][geb_datum] ?></div>

<br>
<input type="submit" name="kunde_aendern" value="Kunde &auml;ndern">

</div>
</form>