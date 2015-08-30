<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<h1><?= t('Добро пожаловать в MaxSite CMS!') ?></h1>

<ul>
	<li><a href="http://max-3000.com/"><?= t('Официальный сайт MaxSite CMS') ?></a> / <a href="http://max-3000.com/page/donation"><?= t('Помочь проекту') ?></a></li>
	
	<li><a href="https://github.com/maxsite/cms"><?= t('Исходный код на GitHub') ?></a> / <a href="https://github.com/maxsite/cms/tree/dev"><?= t('Версия для разработчиков') ?></a></li>
	
	<li><a href="https://github.com/maxsite/cms/issues"><?= t('Сообщить о проблеме') ?></a></li>
	
	<li><a href="http://book.max-3000.com/"><?= t('Обучающая книга по MaxSite CMS') ?></a></li>
	
	<li><a href="http://max-3000.com/help"><?= t('Центр помощи') ?></a> / <a href="http://max-3000.com/page/faq"><?= t('ЧАВО для новичков') ?></a></li>
	
	<li><a href="http://forum.max-3000.com/"><?= t('Форум поддержки') ?></a> / <a href="http://forum.max-3000.com/viewforum.php?f=13"><?= t('Шаблоны') ?></a> / <a href="http://forum.max-3000.com/viewforum.php?f=17"><?= t('Плагины') ?></a></li>
	
	<li><a href="http://maxhub.ru/"><?= t('MaxHub - сообщество MaxSite CMS') ?></a></li>
	
</ul>

<iframe src="http://max-3000.com/check-latest?<?= getinfo('version') ?>" scrolling="auto" frameborder="no" style="width: 100%; min-height: 100px; margin-top: 20px;"></iframe>

<?php

# получать последние новости
	$max_3000_news = mso_get_option('max_3000_news', 'general', 0);
	
	if ($max_3000_news)
	{
		if (!defined('MAGPIE_CACHE_AGE'))	define('MAGPIE_CACHE_AGE', 24*60*60); // время кэширования MAGPIE - 1 сутки
		require_once(getinfo('common_dir') . 'magpierss/rss_fetch.inc');
		$rss = @fetch_rss('http://max-3000.com/feed');

		if ($rss and isset($rss->items) and $rss->items)
		{
			$rss = $rss->items;
			$rss = array_slice($rss, 0, 3); // последние три записи
			
			echo '<h2 class="bor-solid-b bor-gray400 mar20-b mar20-t i-rss">' . t('Новости MaxSite CMS') . '</h2>';
			foreach ($rss as $item)
			{
				// title link category description date_timestamp pubdate
				// if (!isset($item['category'])) $item['category'] = '-';
				
				echo '<h5><a href="' . $item['link'] . '">' . $item['title'] 
						. '</a> - ' . date('d.m.Y', $item['date_timestamp']) . '</h5>';
				echo '<p>' . $item['description'] . '</p>';
				echo '<hr class="dotted mar0-t">';
			}
		}
	}
	
	if (mso_check_allow('admin_home')) // если есть разрешение на доступ
	{
		$show_clear_cache = true;
		
		if ( $post = mso_check_post(array('f_session_id', 'f_submit_clear_cache')) )
		{
			mso_checkreferer();
			$show_clear_cache = false;
			mso_flush_cache(); // сбросим кэш
			// echo '<p>' . t('Кэш удален') . '</p><br>';
			mso_redirect('admin/home');
		}

		if ($show_clear_cache)
		{
			echo '<form method="post">' . mso_form_session('f_session_id');
			
			if ($show_clear_cache)
				echo '<p><button type="submit" name="f_submit_clear_cache" class="button i-stack-overflow">' . t('Сбросить кэш системы') . '</button></p>';

			echo '</form>';
		}
		
	} //if (mso_check_allow('admin_home'))
		
	
		
	mso_hook('admin_home');
	
# end file