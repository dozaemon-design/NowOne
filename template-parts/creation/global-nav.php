		<ul class="p-global-nav__list">
			<li class="p-global-nav__item <?php if (nowone_is_current_nav('home')) echo 'is-current'; ?>">
				<a
					href="<?php echo home_url('/'); ?>"
					class="p-global-nav__link"
					<?php if (nowone_is_current_nav('home')) echo 'aria-current="page"'; ?>
				>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/header/icon_lion.svg" alt="Home Icon">
					HOME
				</a>
			</li>
			<li class="p-global-nav__item <?php if (nowone_is_current_nav('music')) echo 'is-current'; ?>">
				<a
					href="<?php echo home_url('/music/'); ?>"
					class="p-global-nav__link"
					<?php if (nowone_is_current_nav('music')) echo 'aria-current="page"'; ?>
				>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/header/icon_music.svg" alt="Music Icon">
				MUSIC
				</a>
			</li>
			<li class="p-global-nav__item <?php if (nowone_is_current_nav('movie')) echo 'is-current'; ?>">
				<a
					href="<?php echo home_url('/movie/'); ?>"
					class="p-global-nav__link"
					<?php if (nowone_is_current_nav('movie')) echo 'aria-current="page"'; ?>
				>
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/header/icon_movie.svg" alt="Movie Icon">
				MOVIE
				</a>
			</li>

			</ul>
			<ul class="sns">
				<li><a href="https://twitter.com/nowone_though" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/twitter.svg" alt="twitter"></a></li>
				<li><a href="https://www.instagram.com/nowone.design/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/instagram.svg" alt="instagram"></a></li>
				<li><a href="https://www.youtube.com/channel/UCAKLjbh08XL4EX5V4jg5Nig/featured" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/youtube.svg" alt="youtube"></a></li>
			</ul>