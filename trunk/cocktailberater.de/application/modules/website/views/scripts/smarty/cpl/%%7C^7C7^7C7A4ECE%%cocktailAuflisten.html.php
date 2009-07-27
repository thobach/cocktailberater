<?php /* Smarty version 2.6.18, created on 2008-02-03 17:25:17
         compiled from admin/cocktailAuflisten.html */ ?>
<h2>Cocktail Auflisten</h2>
   <table class="list">
      <thead>
         <tr>
            <th align="left">ID</th>
            <th align="left">Name</th>
            <th align="left">Beschreibung</th>
            <th align="left">Erstellt</th>
         </tr>
      </thead>
      <tbody>
         <?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['row']['show'] = true;
$this->_sections['row']['max'] = $this->_sections['row']['loop'];
$this->_sections['row']['step'] = 1;
$this->_sections['row']['start'] = $this->_sections['row']['step'] > 0 ? 0 : $this->_sections['row']['loop']-1;
if ($this->_sections['row']['show']) {
    $this->_sections['row']['total'] = $this->_sections['row']['loop'];
    if ($this->_sections['row']['total'] == 0)
        $this->_sections['row']['show'] = false;
} else
    $this->_sections['row']['total'] = 0;
if ($this->_sections['row']['show']):

            for ($this->_sections['row']['index'] = $this->_sections['row']['start'], $this->_sections['row']['iteration'] = 1;
                 $this->_sections['row']['iteration'] <= $this->_sections['row']['total'];
                 $this->_sections['row']['index'] += $this->_sections['row']['step'], $this->_sections['row']['iteration']++):
$this->_sections['row']['rownum'] = $this->_sections['row']['iteration'];
$this->_sections['row']['index_prev'] = $this->_sections['row']['index'] - $this->_sections['row']['step'];
$this->_sections['row']['index_next'] = $this->_sections['row']['index'] + $this->_sections['row']['step'];
$this->_sections['row']['first']      = ($this->_sections['row']['iteration'] == 1);
$this->_sections['row']['last']       = ($this->_sections['row']['iteration'] == $this->_sections['row']['total']);
?>
            <?php echo '<tr><td valign="top"><a hreF="'; ?><?php echo $this->_tpl_vars['uri']; ?><?php echo 'admin/cocktailLoeschen/idcocktail/'; ?><?php echo $this->_tpl_vars['data'][$this->_sections['row']['index']]['idcocktail']; ?><?php echo '"><img src="'; ?><?php echo $this->_tpl_vars['uri']; ?><?php echo 'img/icons/delete-32x32.png" width="15" /></a> '; ?><?php echo $this->_tpl_vars['data'][$this->_sections['row']['index']]['idcocktail']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['data'][$this->_sections['row']['index']]['cocktail_name']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['data'][$this->_sections['row']['index']]['cocktail_beschreibung']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['data'][$this->_sections['row']['index']]['cocktail_datum']; ?><?php echo '</td></tr>'; ?>

         <?php endfor; endif; ?>
      </tbody>
   </table>
   