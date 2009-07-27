<?php /* Smarty version 2.6.18, created on 2008-02-03 17:29:08
         compiled from kunde/kundeAnlegen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_image', 'kunde/kundeAnlegen.html', 4, false),array('function', 'html_select_date', 'kunde/kundeAnlegen.html', 33, false),)), $this); ?>
<form action="" method="post" enctype="multipart/form-data"
	name="kundeAnlegenForm">
<div>
<div><?php echo smarty_function_html_image(array('file' => ($this->_tpl_vars['logo'])), $this);?>
</div>
<br>
<h2>Kunde anlegen:</h2>

<?php $this->assign('feld', 'vorname'); ?>
<div class="box"><strong>Vorname:</strong></div>
<div><input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
"></div>


<?php $this->assign('feld', 'nachname'); ?>
<div class="box"><strong>Nachname:</strong></div>
<div><input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
"></div>


<?php $this->assign('feld', 'email'); ?>
<div class="box"><strong>E-Mail Adresse:</strong></div>
<div><input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
"></div>

<?php $this->assign('feld', 'passwort'); ?>
<div class="box"><strong>Passwort:</strong></div>
<div><input type="password" name="<?php echo $this->_tpl_vars['feld']; ?>
"
	value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
"></div>

<?php $this->assign('feld', 'geb_datum'); ?>
<div class="box"><strong>Geburtsdatum:</strong></div>
<div> <?php echo smarty_function_html_select_date(array('start_year' => -1,'end_year' => -100,'field_order' => 'DMY','time' => "1986-01-01"), $this);?>
</div>

<br>
<input type="submit" name="kunde_anlegen" value="Kunde anlegen">

</div>
</form>