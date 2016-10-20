<div class="home-page-carousel top-banner">
  <h1 class="top-banner__title">אנגלית אקדמית</h1>
  <div class="top-banner__sub-title">
    <h4>כלים ומיומנויות לקריאת מאמרים אקדמיים באנגלית</h4>
    <h4>חומרי הלימוד פתוחים לכל <b>בחינם</b></h4>
  </div>
  <?php if (!isloggedin()){?>
          <a class="button button--blue top-banner__button" href="<?php echo new moodle_url('/login/index.php')?>"><?php echo get_string('start-study','theme_enlight')?></a>
  <?php } ?>
</div>