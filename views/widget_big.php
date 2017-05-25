<div class="ttvw">

    <?php foreach ($channels_data as $channel) { ?>

        <div class="tp-ttvw-big-box-in-wrapper<?php echo $style; ?>">

            <!-- LIVE VIEW OR PICTURE -- TOP -->

            <?php if ($banner_postion == 'top') { ?>
                <div class="tp-ttvw-big-box-username" style="margin-top: -5px; margin-bottom: 5px">
                    <a target="_blank"
                       href="https://www.twitch.tv/<?php echo $channel['username']; ?>/"> <?php echo $channel['display_name']; ?>
                    </a>
                </div>

                <div>
                    <a target="_blank" href="https://www.twitch.tv/<?php echo $channel['username']; ?>/">
                        <?php if ($live_or_picture == 'twitch_picture') { ?>
                            <img class="tp-ttvw-big-box-live-pic" src="<?php echo $channel['preview_large'] ?>"/>
                        <?php } else { ?>

                            <iframe
                                src="http://player.twitch.tv/?channel=<?php echo $channel['display_name'] ?>&muted=true"
                                height="<?php echo $live_or_picture_height ?>"
                                width="<?php echo $live_or_picture_width ?>"
                                frameborder="0"
                                scrolling="no"
                                allowfullscreen="true">
                            </iframe>

                        <?php } ?>
                    </a>
                </div>
            <?php } ?>

            <!-- LIVE VIEW OR PICTURE -- END -->

            <!-- LIVE VIEW OR PICTURE -- MIDDLE -->

            <?php if ($banner_postion == 'middle') { ?>
            <div>
                <a target="_blank" href="https://www.twitch.tv/<?php echo $channel['username']; ?>/">
                    <?php if ($live_or_picture == 'twitch_picture') { ?>
                        <img class="tp-ttvw-big-box-live-pic" src="<?php echo $channel['preview_large'] ?>"/>
                    <?php } else { ?>

                        <iframe
                            src="http://player.twitch.tv/?channel=<?php echo $channel['display_name'] ?>&muted=true"
                            height="<?php echo $live_or_picture_height ?>"
                            width="<?php echo $live_or_picture_width ?>"
                            frameborder="0"
                            scrolling="no"
                            allowfullscreen="true">
                        </iframe>

                    <?php } ?>
                </a>
            </div>

            <div class="tp-ttvw-big-box-username">
                <a target="_blank"
                   href="https://www.twitch.tv/<?php echo $channel['username']; ?>/"> <?php echo $channel['display_name']; ?>
                </a>
            </div>
            <?php } ?>

            <!-- LIVE VIEW OR PICTURE -- END -->

            <?php if (isset ($channel['aktiv_game']) ) { ?>
            <div class="tp-ttvw-big-box-is-playing-wrapper">
                <div class="tp-ttvw-big-box-is-playing-fix"><?php _e( 'Is Currently Playing:', 'tp-ttvw' ); ?></div>
                <div class="tp-ttvw-big-box-is-playing"><?php echo $channel['aktiv_game']; ?></div>
            </div>
            <?php } ?>

            <?php if ($hide_title == '1') { ''; }
            else { ?>
                <div class="tp-ttvw-big-box-title-wrapper">
                    <div class="tp-ttvw-big-box-title-fix"<?php if (!empty($font_color)) echo ' style="color:' . $font_color . ';"'; ?>><?php _e( 'Title:', 'tp-ttvw' ); ?></div>
                    <div class="tp-ttvw-big-box-title"<?php if (!empty($font_color)) echo ' style="color:' . $font_color . ';"'; ?>><?php echo $channel['channel_title']; ?></div>
                </div>
            <?php } ?>

            <?php if ($hide_stats == '1') { ''; }
            else { ?>
            <div class="tp-ttvw-big-box-stats">
                <span class="tp-ttvw-big-box-stats-item"<?php if (!empty($font_color)) echo ' style="color:' . $font_color . ';"'; ?>><img
                        src="<?php tp_ttvw_the_assets(); ?>img/live.png"/> <?php echo $channel['viewers']; ?></span>
                <span class="tp-ttvw-big-box-stats-item"<?php if (!empty($font_color)) echo ' style="color:' . $font_color . ';"'; ?>><img
                        src="<?php tp_ttvw_the_assets(); ?>img/subs.png"/> <?php echo $channel['followers']; ?></span>
                <span class="tp-ttvw-big-box-stats-item"<?php if (!empty($font_color)) echo ' style="color:' . $font_color . ';"'; ?>><img
                        src="<?php tp_ttvw_the_assets(); ?>img/views.png"/> <?php echo $channel['views']; ?></span>
            </div>
            <?php } ?>

        </div>

    <?php } ?>

</div>