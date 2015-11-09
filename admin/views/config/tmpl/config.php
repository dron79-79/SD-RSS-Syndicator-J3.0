<?php defined('_JEXEC') or die('Restricted access');
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
// Загружаем украшательства формы.
JHtml::_('formbehavior.chosen', 'select');
?>
<?php /*<script>
			function loadImg(elem) {
				document.getElementById("feedImg").src = elem.value;
			}
		</script><?php */?>
		<?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
<?php else : ?>
    <div id="j-main-container">
<?php endif;?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	<table border="0" cellpadding="3" cellspacing="0" align="left">
	<tr>
		<td>Показываемый текст</td><td><textarea name="msg" cols="80" rows="3" ><?php echo $this->msg; ?></textarea></td>
	</tr>
	<tr>
		<td>Описание ленты RSS</td><td><textarea name="description" cols="80" rows="3" ><?php echo $this->description; ?></textarea></td>
	</tr>
	<tr>
		<td>Тип создаваемого канала RSS:</td><td><?php echo $this->defaultType; ?></td>
	</tr>
	<tr>
		<td>количество показываемых новостей в канале: </td><td><input type="text" size="3" maxlength="3" name="count" value="<?php echo $this->count; ?>" /></td>
	</tr>
	<tr>
		<td>Сортировка</td><td><?php echo $this->orderby; ?></td>
	</tr>
	<tr>
		<td>Выберете общее количество слов показываемых в канале</td><td><?php echo $this->numWords; ?></td>
	</tr>
	<tr>
		<td>Включить имя и email автора?</td><td><?php echo $this->renderAuthorFormat; ?></td>
	</tr>
	<tr>
		<td>Внедрять HTML?</td><td><?php echo $this->renderHTML;?></td>
	</tr>
	<tr>
		<td>тег &lt;category&gt: </td><td><?php echo $this->categoryItem;?></td>
	</tr>

	<tr>
		<td>Только статьи с главной?</td><td><?php echo $this->FPItemsOnly;?></td>
	</tr>
	<tr>
		<td>Время кеширования в секундах</td><td><input type="text" size="10" maxlength="10" name="cache" value="<?php echo $this->cache; ?>" /></td>
	</tr>
        <tr>
		<td>Часовой пояс</td><td><input type="text" size="3" maxlength="3" name="tp" value="<?php echo $this->tp; ?>" /></td>
	</tr>
	<?php /*?><tr>
		<td>Url of feed image</td><td><input onchange="loadImg(this)" type="text" size="20" maxsize="100" name="imgUrl" value="<?php echo $this->imgUrl; ?>" />&nbsp;[Tab] to preview or leave blank to leave out image details from feed</td>
	</tr>
	<tr>
		<td >&nbsp;</td><td valign="top">Image Preview:<br /> <img id="feedImg" src="<?php echo $this->imgUrl; ?>" /></td>
	</tr><?php */?>
	</table>
	<input type="hidden" name="id" value="<?php echo $this->id;?>" />
	<input type="hidden" name="option" value="com_sdrsssyndicator" />
	<input type="hidden" name="controller" value="config" />
	<input type="hidden" name="task" value="" />
	</form>