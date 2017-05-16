<div class="ttvw">

    <?php foreach ($channels_data as $channel) { ?>

        <div class="tp-ttvw-small-box-in-wrapper tp-ttvw-clearfix<?php echo $style; ?>">

            <div>
                <a target="_blank" href="https://www.twitch.tv/<?php echo $channel['username']; ?>/">
                    <img class="tp-ttvw-small-box-live-pic" src="<?php echo $channel['image'] ?>"/>
                </a>
            </div>

            <div class="tp-ttvw-small-box-name">
                <a target="_blank" href="https://www.twitch.tv/<?php echo $channel['username'] ?>/">
                    <?php echo $channel['display_name']; ?>
                </a>
            </div>

            <?php if ($channel['live']) { ?>
                <div class="tp-ttvw-small-box-viewers">
                    <span class="tp-ttvw-punkt-small-box-online"></span> <?php _e( 'Viewer:', 'tp-ttvw' ); ?> <?php echo $channel['viewers']; ?>
                </div>
            <?php } else { ?>

                <div class="tp-ttvw-small-box-viewers">
                    <span class="tp-ttvw-punkt-small-box-offline"></span> Offline :'(
                </div>

            <?php } ?>

        </div>

    <?php } ?>

</div>