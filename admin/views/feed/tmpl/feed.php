<?php defined('_JEXEC') or die('Restricted access');
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/
// Загружаем тултипы.
JHtml::_('bootstrap.tooltip');
// Загружаем проверку формы.
JHtml::_('behavior.formvalidation');
// Загружаем украшательства формы.
JHtml::_('formbehavior.chosen', 'select');
// Получаем параметры из формы.
//$params = $this->form->getFieldsets('params');
?>


<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
    <table border="0" cellpadding="3" cellspacing="0" align="left">
        <tr>
            <td>Имя канала:</td>
            <td><input type="text" size="50" maxlength="150" name="feed_name" value="<?php echo($this->name); ?>" /></td>
        </tr>
        <tr>
            <td>описание канала RSS</td><td><?php echo $this->editor->display( 'feed_description',  $this->description, 500, 200, 70, 20, 1 ) ; ?></td>
        </tr>
        <tr>
            <td>Тип канала RSS:</td><td><?php echo $this->lists['rssTypeList']; ?></td>
        </tr>
		<tr>
            <td>Жанр сообщений(только для Yandex):</td><td><?php echo $this->lists['rssYandexGenreList']; ?></td>
        </tr>
        <tr>
            <td>Количество новостей показываемых в канале: </td><td><input type="text" size="3" maxlength="3" name="msg_count" value="<?php echo $this->count; ?>" /></td>
        </tr>
        <tr>
            <td>Порядок</td><td><?php echo $this->lists['orderingList']; ?></td>
        </tr>
        <?php /*>> MAD 2007/10/10 */  ?>
        <tr>
            <td>Выберете количество слов показываемых в новости</td><td><?php echo $this->lists['numWordsList']; ?></td>
        </tr>
        <tr>
            <td>Полный текст</td><td><?php echo $this->lists['fulltextlist'];?></td>
        </tr>
        <?php /*<< MAD 2007/10/10 */  ?>
        <tr>
            <td>включать почту автора и имя?</td><td><?php echo $this->lists['renderAuthorList']; ?></td>
        </tr>
        <tr>
            <td>включить HTML?</td><td><?php echo $this->lists['renderHTMLList'];?></td>
        </tr>
        <tr>
            <td>включить Images?</td><td><?php echo $this->lists['renderImagesList'];?></td>
        </tr>
		<tr>
            <td>тег &lt;category&gt: </td><td><?php echo $this->lists['renderCategoryList'];?></td>
        </tr>
        <tr>
            <td>Frontpage Items only?</td><td><?php echo $this->lists['FPItemsOnlyList'];?></td>
        </tr>
        <tr>
            <td>Кеширование в секундах</td><td><input type="text" size="10" maxlength="10" name="feed_cache" value="<?php echo $this->cache; ?>" /></td>
        </tr>
        <tr>
            <td>Опубликовать?</td><td><?php echo $this->lists['renderPublishedList'];?></td>
        </tr>

        <tr>
            <td>Включить или исключить категории?</td><td><?php echo $this->lists['includeCats'];?></td>
        </tr>
        <?php /*>> AGE 2007/09/25 */  ?>
        <tr>
            <td>Выберете категорию</td><td><?php echo $this->lists['excludedcatlist'];?></td>
        </tr>
        
            <td>Исключенные статьи</td>
            <td>
                <textarea name="msg_exitems" cols="30" rows="3" ><?php echo $this->exitems; ?></textarea>
                <br />Введите id (идентификатор) статьи(ей) , которые требуется исключить.
                <br />в качестве разделителя использовать ",". <br />пример: 1, 2, 3, 4, 5, 6
            </td>
        </tr>
        <tr>
            <td>Изображение канала<br />
			</td>
            <td>
                <input onchange="loadImg(this)" type="text" size="20" name="feed_imgUrl" value="<?php echo $this->imgUrl; ?>" />&nbsp;[Tab] to preview or leave blank to leave out image details from feed
            </td>
        </tr>
		 <tr>
            <td>Ключ для выборки статьи, для показа в канале<br />
			</td>
            <td>
                <input  type="text" size="20" name="feed_key" value="<?php echo $this->key; ?>" />&nbsp;
            </td>
        </tr>
        <tr>
            <td >Предпросмотр изображения канала:</td><td valign="top"><img id="feedImg" src="<?php echo $this->imgUrl; ?>" /></td>
        </tr>
        <tr>
            <td>кнопка канала</td>
            <td><?php echo($this->lists['feedButtons']);?>&nbsp;
                <img id="feedButton" src="<?php echo (JURI::root() . "components/com_sdrsssyndicator/assets/images/buttons/" . $this->BtnImgUrl); ?>" />
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="<?php echo $this->id;?>" />
    <input type="hidden" name="option" value="com_sdrsssyndicator" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="feed" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
<script type="text/javascript">
    checkRenderHtml();

    $('feed_renderHTML').addEvent('change', function(){
        checkRenderHtml();
    });
    $('feed_renderImages').addEvent('change', function(){
        checkRenderHtml();
    });

    function checkRenderHtml()
    {
        if($('feed_renderHTML').value == 0)
        {
            $('feed_renderImages').value = 0;
            $('feed_renderImages').setProperty('disabled','disabled');
        }
        else
        {
            $('feed_renderImages').setProperty('disabled','');
        }
    }
	function checkYandex()
    {
        if($('feed_type').value == 'RSS 2.0 vs Яндекс')
        {
            $('feed_renderImages').value = 0;
			$('feed_renderHTML').value = 0;
            $('feed_renderImages').setProperty('disabled','disabled');
			$('feed_renderHTML').setProperty('disabled','disabled');
        }
        else
        {
            $('feed_renderImages').setProperty('disabled','');
			$('feed_renderHTML').setProperty('disabled','');
        }
    }
    function loadImg(elem) {
        document.getElementById("feedImg").src = elem.value;
    }
    function loadButton(elem) {
        document.getElementById("feedButton").src = '<?php echo(JURI::root() . "components/com_sdrsssyndicator/assets/images/buttons/");?>' + elem.value;
    }
</script>