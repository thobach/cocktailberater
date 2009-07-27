<?php /* Smarty version 2.6.18, created on 2008-02-03 17:59:09
         compiled from webclient/warteliste.html */ ?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
  <h3>Fertige Cocktails<hr /></h3>
  
  <table class="list" style="width:400px">
      <thead>
         <tr>
            <th align="left">Wer</th>
            <th align="left">Cocktail</th>
         </tr>
      </thead>
      <tbody>
         <?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['fertig']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <?php echo '<tr><td valign="top">'; ?><?php echo $this->_tpl_vars['fertig'][$this->_sections['row']['index']]['Wer']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['fertig'][$this->_sections['row']['index']]['Cocktail']; ?><?php echo ' <a href="?abgeholt='; ?><?php echo $this->_tpl_vars['fertig'][$this->_sections['row']['index']]['ID']; ?><?php echo '">[abgeholt]</a></td></tr>'; ?>

         <?php endfor; endif; ?>
      </tbody>
   </table>
  <br />
  <h3>in Bearbeitung<hr /></h3>
  <table class="list" style="width:400px">
      <thead>
         <tr>
            <th align="left">Wer</th>
            <th align="left">Cocktail</th>
         </tr>
      </thead>
      <tbody>
         <?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['inBearbeitung']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <?php echo '<tr><td valign="top">'; ?><?php echo $this->_tpl_vars['inBearbeitung'][$this->_sections['row']['index']]['Wer']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['inBearbeitung'][$this->_sections['row']['index']]['Cocktail']; ?><?php echo '</td></tr>'; ?>

         <?php endfor; endif; ?>
      </tbody>
   </table>
  <br />
  <h3>wartend<hr /></h3>
  <table class="list" style="width:400px">
      <thead>
         <tr>
            <th align="left">Wer</th>
            <th align="left">Cocktail</th>
         </tr>
      </thead>
      <tbody>
         <?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['entgegengenommen']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <?php echo '<tr><td valign="top">'; ?><?php echo $this->_tpl_vars['entgegengenommen'][$this->_sections['row']['index']]['Wer']; ?><?php echo '</td><td valign="top">'; ?><?php echo $this->_tpl_vars['entgegengenommen'][$this->_sections['row']['index']]['Cocktail']; ?><?php echo '</td></tr>'; ?>

         <?php endfor; endif; ?>
      </tbody>
   </table>
</body>
</html>