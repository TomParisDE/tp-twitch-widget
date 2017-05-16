<div class="ttvw">

    <?php foreach ($channels_data as $channel) { ?>

        <div class="tp-ttvw-big-box-in-wrapper<?php echo $style; ?>">

            <div>
                <a target="_blank" href="https://www.twitch.tv/<?php echo $channel['username']; ?>/">
                    <img class="tp-ttvw-big-box-live-pic" src="<?php echo $channel['preview_large'] ?>"/>
                </a>
            </div>

            <div class="tp-ttvw-big-box-username">
                <a target="_blank"
                   href="https://www.twitch.tv/<?php echo $channel['username']; ?>/"> <?php echo $channel['display_name']; ?>
                </a>
            </div>

            <div class="tp-ttvw-big-box-is-playing-wrapper">
                <div class="tp-ttvw-big-box-is-playing-fix"><?php _e( 'Is Currently Playing:', 'tp-ttvw' ); ?></div>
                <div class="tp-ttvw-big-box-is-playing"><?php echo $channel['aktiv_game']; ?></div>
            </div>

            <div class="tp-ttvw-big-box-title-wrapper">
                <div class="tp-ttvw-big-box-title-fix"><?php _e( 'Title:', 'tp-ttvw' ); ?></div>
                <div class="tp-ttvw-big-box-title"><?php echo $channel['channel_title']; ?></div>
            </div>

            <div class="tp-ttvw-big-box-stats">
                <span class="tp-ttvw-big-box-stats-item"><img
                        src="<?php tp_ttvw_the_assets(); ?>img/live.png"/> <?php echo $channel['viewers']; ?></span>
                <span class="tp-ttvw-big-box-stats-item"><img
                        src="<?php tp_ttvw_the_assets(); ?>img/subs.png"/> <?php echo $channel['followers']; ?></span>
                <span class="tp-ttvw-big-box-stats-item"><img
                        src="<?php tp_ttvw_the_assets(); ?>img/views.png"/> <?php echo $channel['views']; ?></span>
            </div>

        </div>

    <?php } ?>

</div>