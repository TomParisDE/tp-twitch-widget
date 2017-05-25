<?php

/**
 * Adds Foo_Widget widget.
 */
class TP_TTVW_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'tp_ttvw_widget', // Base ID
            __( 'TomParisDE Twitch Widget', 'tp-ttvw' ), // Name
            array( 'description' => __( 'Your TomParisDE Twitch Widget for Blogs, Clan-, Fan- and Community Sites', 'tp-ttvw' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        if ( ! empty ( $instance['channel'] ) || ! empty ( $instance['twitch_game'] ) && ! empty ( $instance['api_key'] ) ) {

            @$api_key = $instance['api_key'];
            @$hide_offline_channels = $instance['hide_offline_channels'];
            @$hide_title = $instance['hide_title'];
            @$hide_stats = $instance['hide_stats'];
            @$banner_postion = $instance['banner_postion'];
            @$template = $instance['template'];
            @$style = " " . $instance['style'];
            @$font_color = $instance['font_color'];
            @$twitch_game = $instance['twitch_game'];
            @$max_games = $instance['max_games'];
            @$channel_or_game = $instance['channel_or_game'];
            @$twitch_streamer_language = $instance['twitch_streamer_language'];
            @$live_or_picture = $instance['live_or_picture'];
            @$live_or_picture_height = $instance['live_or_picture_height'];
            @$live_or_picture_width = $instance['live_or_picture_width'];


            // Data for output
            $channels_data = array();


            // Get channels from user
            $channels = str_replace(' ', '', $instance['channel']);
            $channels = explode(",", $channels );


            // channel or game question
            if ( $channel_or_game == 'channels' )
            {
                foreach ( $channels as $channel)
                {
                    $channels_data[] = tp_get_channel_data ( $api_key, $channel );
                } }
            else {
                $channels_data = tp_get_channel_or_game_data( $api_key, $twitch_game, $max_games, $twitch_streamer_language );
            }

            // if offline dont show
            if ( $hide_offline_channels == '1') {
                foreach ( $channels_data as $key => $channel) {
                    if ( $channel['live'] != 1 ) {
                        unset($channels_data[$key]);
                    }
                }
            }

            // Sort by Viewers
            if ( is_array($channels_data) && sizeof ( $channels_data ) > 0 ) {
                foreach ($channels_data as $key => $row) {
                    @$viewers[$key] = $row['viewers'];
                }

                array_multisort($viewers, SORT_DESC, $channels_data);

            }

            // don't show view if 0 channels + mix big box online with small offline box
            if ( sizeof( $channels_data ) > 0 ) {

                echo $args['before_widget'];
                if ( ! empty( $instance['title'] ) ) {
                    echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
                }

                if ( $template == 'widget_big' ) {
                    $channels_data_big = array();
                    $channels_data_small = array();


                    // Array trennen
                    foreach ( $channels_data as $key => $channel) {
                        if ( $channel['live'] == 1 ) {
                            $channels_data_big[] = $channel;
                        }
                        else {
                            $channels_data_small[] = $channel;
                        }
                    }

                    //bis ausaugeben
                    if ( sizeof( $channels_data_big ) > 0 ) {
                        $channels_data = $channels_data_big;
                        include TP_TTVW_DIR . 'views/widget_big.php';
                    }

                    // small ausgeben
                    if ( sizeof( $channels_data_small ) > 0 ) {
                        $channels_data = $channels_data_small;
                        include TP_TTVW_DIR . 'views/widget_small.php';
                    }
                }
                else {
                    include TP_TTVW_DIR . 'views/' . $template . '.php';
                }

                echo $args['after_widget'];

            } else {
                //echo 'keine Channels online';
            }



        } else {
            // Hinweis keine Channels übergeben
        }

    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $api_key = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $channel = ! empty( $instance['channel'] ) ? $instance['channel'] : '';
        $hide_offline_channels = ! empty( $instance['hide_offline_channels'] ) ? $instance['hide_offline_channels'] : '';
        $hide_title = ! empty( $instance['hide_title'] ) ? $instance['hide_title'] : '';
        $hide_stats = ! empty( $instance['hide_stats'] ) ? $instance['hide_stats'] : '';
        $banner_postion = ! empty( $instance['banner_postion'] ) ? $instance['banner_postion'] : '';
        $template = ! empty( $instance['template'] ) ? $instance['template'] : '';
        $style = ! empty( $instance['style'] ) ? $instance['style'] : '';
        $font_color = ! empty( $instance['font_color'] ) ? $instance['font_color'] : '';
        $twitch_game = ! empty( $instance['twitch_game'] ) ? $instance['twitch_game'] : '';
        $max_games = ! empty( $instance['max_games'] ) ? $instance['max_games'] : '';
        $channel_or_game = ! empty( $instance['channel_or_game'] ) ? $instance['channel_or_game'] : '';
        $twitch_streamer_language = ! empty( $instance['twitch_streamer_language'] ) ? $instance['twitch_streamer_language'] : '';
        $live_or_picture = ! empty( $instance['live_or_picture'] ) ? $instance['live_or_picture'] : '';
        $live_or_picture_height = ! empty( $instance['live_or_picture_height'] ) ? $instance['live_or_picture_height'] : '';
        $live_or_picture_width = ! empty( $instance['live_or_picture_width'] ) ? $instance['live_or_picture_width'] : '';

        ?>

        <p style="font-size: 24px; font-weight: 600;"><?php _e( 'Global Settings', 'tp-ttvw' ); ?></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e( 'Twitch API Key:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'api_key' ); ?>" name="<?php echo $this->get_field_name( 'api_key' ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'channel_or_game' ); ?>"><?php _e( 'Channel(s) or Game:', 'tp-ttvw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'channel_or_game' ); ?>" name="<?php echo $this->get_field_name( 'channel_or_game' ); ?>">
                <option value="channels"<?php selected( $channel_or_game, "channels" ); ?>><?php _e( 'Channel(s)', 'tp-ttvw' ); ?></option>
                <option value="game"<?php selected( $channel_or_game, "game" ); ?>><?php _e( 'Game', 'tp-ttvw' ); ?></option>
            </select>
        </p>

        <p style="font-size: 24px; font-weight: 600;"><?php _e( 'Twitch Channel Settings', 'tp-ttvw' ); ?></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'channel' ); ?>"><?php _e( 'Twitch Channel(s):', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'channel' ); ?>" name="<?php echo $this->get_field_name( 'channel' ); ?>" type="text" value="<?php echo esc_attr( $channel ); ?>">
        </p>

        <p style="font-size: 24px; font-weight: 600;"><?php _e( 'Twitch Game Settings', 'tp-ttvw' ); ?></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'twitch_game' ); ?>"><?php _e( 'Twitch Game:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitch_game' ); ?>" name="<?php echo $this->get_field_name( 'twitch_game' ); ?>" type="text" value="<?php echo esc_attr( $twitch_game ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'max_games' ); ?>"><?php _e( 'Max Streams (1-100):', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'max_games' ); ?>" name="<?php echo $this->get_field_name( 'max_games' ); ?>" type="text" value="<?php echo esc_attr( $max_games ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'twitch_streamer_language' ); ?>"><?php _e( 'Twitch Streamer Language:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitch_streamer_language' ); ?>" name="<?php echo $this->get_field_name( 'twitch_streamer_language' ); ?>" type="text" value="<?php echo esc_attr( $twitch_streamer_language ); ?>">
        </p>

        <p style="font-size: 24px; font-weight: 600;"><?php _e( 'Front-end Output', 'tp-ttvw' ); ?></p>

        <p>
            <label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _e( 'Template:', 'tp-ttvw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>">
                <option value="widget_small"<?php selected( $template, "widget_small" ); ?>><?php _e( 'Small', 'tp-ttvw' ); ?></option>
                <option value="widget_big"<?php selected( $template, "widget_big" ); ?>><?php _e( 'Big', 'tp-ttvw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style:', 'tp-ttvw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
                <option value="light"<?php selected( $style, "light" ); ?>><?php _e( 'Light', 'tp-ttvw' ); ?></option>
                <option value="dark"<?php selected( $style, "dark" ); ?>><?php _e( 'Dark', 'tp-ttvw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'live_or_picture' ); ?>"><?php _e( 'Live View or Live Preview Picture: (Big Template)', 'tp-ytw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'live_or_picture' ); ?>" name="<?php echo $this->get_field_name( 'live_or_picture' ); ?>">
                <option value="twitch_live"<?php selected( $live_or_picture, "twitch_live" ); ?>><?php _e( 'Live View', 'tp-ytw' ); ?></option>
                <option value="twitch_picture"<?php selected( $live_or_picture, "twitch_picture" ); ?>><?php _e( 'Live Preview Picture', 'tp-ytw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'live_or_picture_height' ); ?>"><?php _e( 'Live View (Height): (Big Template)', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'live_or_picture_height' ); ?>" name="<?php echo $this->get_field_name( 'live_or_picture_height' ); ?>" placeholder="auto" type="text" value="<?php echo esc_attr( $live_or_picture_height ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'live_or_picture_width' ); ?>"><?php _e( 'Live View (Width): (Big Template)', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'live_or_picture_width' ); ?>" name="<?php echo $this->get_field_name( 'live_or_picture_width' ); ?>" placeholder="auto" type="text" value="<?php echo esc_attr( $live_or_picture_width ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'banner_postion' ); ?>"><?php _e( 'Live View or Live Preview Picture Position: (Big Template)', 'tp-ytw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'banner_postion' ); ?>" name="<?php echo $this->get_field_name( 'banner_postion' ); ?>">
                <option value="top"<?php selected( $banner_postion, "top" ); ?>><?php _e( 'Top', 'tp-ytw' ); ?></option>
                <option value="middle"<?php selected( $banner_postion, "middle" ); ?>><?php _e( 'Middle', 'tp-ytw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'font_color' ); ?>"><?php _e( 'Font Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'font_color' ); ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" placeholder="#000" type="text" value="<?php echo esc_attr( $font_color ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e( 'Hide Title', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" type="checkbox" value="1" <?php echo(@$instance['@hide_title'] == 1 ? 'checked' : ''); ?>  >
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'hide_stats' ); ?>"><?php _e( 'Hide Statistics', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'hide_stats' ); ?>" name="<?php echo $this->get_field_name( 'hide_stats' ); ?>" type="checkbox" value="1" <?php echo(@$instance['hide_stats'] == 1 ? 'checked' : ''); ?>  >
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'hide_offline_channels' ); ?>"><?php _e( 'Hide Offline User (Channel Settings)', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'hide_offline_channels' ); ?>" name="<?php echo $this->get_field_name( 'hide_offline_channels' ); ?>" type="checkbox" value="1" <?php echo(@$instance['hide_offline_channels'] == 1 ? 'checked' : ''); ?>  >
        </p>

        <?php
    }


    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['api_key'] = ( ! empty( $new_instance['api_key'] ) ) ? strip_tags( $new_instance['api_key'] ) : '';
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['channel'] = ( ! empty( $new_instance['channel'] ) ) ? strip_tags( $new_instance['channel'] ) : '';
        $instance['hide_offline_channels'] = ( ! empty( $new_instance['hide_offline_channels'] ) ) ? strip_tags( $new_instance['hide_offline_channels'] ) : '';
        $instance['hide_title'] = ( ! empty( $new_instance['hide_title'] ) ) ? strip_tags( $new_instance['hide_title'] ) : '';
        $instance['hide_stats'] = ( ! empty( $new_instance['hide_stats'] ) ) ? strip_tags( $new_instance['hide_stats'] ) : '';
        $instance['template'] = ( ! empty( $new_instance['template'] ) ) ? strip_tags( $new_instance['template'] ) : 'widget_small';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : 'light';
        $instance['banner_postion'] = ( ! empty( $new_instance['banner_postion'] ) ) ? strip_tags( $new_instance['banner_postion'] ) : 'middle';
        $instance['font_color'] = ( ! empty( $new_instance['font_color'] ) ) ? strip_tags( $new_instance['font_color'] ) : '';
        $instance['twitch_game'] = ( ! empty( $new_instance['twitch_game'] ) ) ? strip_tags( $new_instance['twitch_game'] ) : '';
        $instance['max_games'] = ( ! empty( $new_instance['max_games'] ) ) ? strip_tags( $new_instance['max_games'] ) : '';
        $instance['channel_or_game'] = ( ! empty( $new_instance['channel_or_game'] ) ) ? strip_tags( $new_instance['channel_or_game'] ) : '';
        $instance['twitch_streamer_language'] = ( ! empty( $new_instance['twitch_streamer_language'] ) ) ? strip_tags( $new_instance['twitch_streamer_language'] ) : '';
        $instance['live_or_picture'] = ( ! empty( $new_instance['live_or_picture'] ) ) ? strip_tags( $new_instance['live_or_picture'] ) : '';
        $instance['live_or_picture_height'] = ( ! empty( $new_instance['live_or_picture_height'] ) ) ? strip_tags( $new_instance['live_or_picture_height'] ) : '';
        $instance['live_or_picture_width'] = ( ! empty( $new_instance['live_or_picture_width'] ) ) ? strip_tags( $new_instance['live_or_picture_width'] ) : '';


        return $instance;
    }

} // class Foo_Widget


// register TP Twitch widget
function tp_ttvw_register_widget() {
    register_widget( 'TP_TTVW_Widget' );
}
add_action( 'widgets_init', 'tp_ttvw_register_widget' );