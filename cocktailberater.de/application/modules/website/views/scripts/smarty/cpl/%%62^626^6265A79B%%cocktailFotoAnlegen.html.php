<?php /* Smarty version 2.6.18, created on 2008-02-03 17:28:10
         compiled from admin/cocktailFotoAnlegen.html */ ?>
<form action="" method="post" enctype="multipart/form-data" name="cocktailFotoAnlegenForm">

<div>  

<h2>Foto:</h2> 
     


 <div class="box">
<?php $this->assign('feld', 'rezept_foto'); ?>
<strong>Foto:</strong>
<input type="file" name="rezept_foto">
</div>

 <div class="box">
<?php $this->assign('feld', 'cocktail_anlegen'); ?>
<br><br>

<input type="submit" name="cocktail_foto_anlegen" value="Foto speichern">
</div>

</div>

</form>