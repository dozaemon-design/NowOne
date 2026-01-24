<?php
$current = nowone_get_current_segment();

$nav_items = [
  'home' => [
    'url' => '/',
    'label' => 'HOME',
    'icon' => 'icon_lion.svg',
    'alt'  => 'Home Icon',
  ],
  'music' => [
    'url' => '/music/',
    'label' => 'MUSIC',
    'icon' => 'icon_music.svg',
    'alt'  => 'Music Icon',
  ],
  'movie' => [
    'url' => '/movie/',
    'label' => 'MOVIE',
    'icon' => 'icon_movie.svg',
    'alt'  => 'Movie Icon',
  ],
  'about' => [
    'url' => '/about/',
    'label' => 'ABOUT',
    'icon' => 'icon_about.svg',
    'alt'  => 'about Icon',
  ],
    'contact' => [
    'url' => '/contact/',
    'label' => 'CONTACT',
    'icon' => 'icon_contact.svg',
    'alt'  => 'contact Icon',
  ],
];
?>

<ul class="c-global-nav__list">
<?php foreach ($nav_items as $key => $item): ?>
  <?php
    $is_current =
      ($key === 'home' && $current === '')
      || ($current === $key);
  ?>
  <li class="c-global-nav__item <?= $is_current ? 'is-current' : '' ?>">
    <a
      href="<?= home_url($item['url']) ?>"
      class="c-global-nav__link"
      <?= $is_current ? 'aria-current="page"' : '' ?>
    >
      <img
        src="<?= get_template_directory_uri(); ?>/assets/img/common/header/<?= esc_attr($item['icon']) ?>"
        alt="<?= esc_attr($item['alt']) ?>" width="100%" height="auto"
      >
      <?= esc_html($item['label']) ?>
    </a>
  </li>
<?php endforeach; ?>
</ul>

<ul class="c-sns-list">
  <li class="c-sns-list__item">
    <a href="https://twitter.com/nowone_though" target="_blank">
      <picture>
        <figure>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/X.svg" alt="X icon" width="100%" height="auto">
        </figure>
      </picture>
    </a>
  </li>
  <li class="c-sns-list__item">
    <a href="https://www.instagram.com/nowone.design/" target="_blank">
      <picture>
        <figure>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/instagram.svg" alt="instagram icon" width="100%" height="auto">
        </figure>
      </picture>
    </a>
  </li>
  <li class="c-sns-list__item">
    <a href="https://www.youtube.com/channel/UCAKLjbh08XL4EX5V4jg5Nig/featured" target="_blank">
      <picture>
        <figure>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/sns/youtube.svg" alt="youtube icon" width="100%" height="auto">
        </figure>
      </picture>
    </a>
  </li>
</ul>