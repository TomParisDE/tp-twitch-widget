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
            __( 'TomParisDE TwitchTV Widget', 'tp-ttvw' ), // Name
            array( 'description' => __( 'Your TwitchTV Widget to show you your online or offline status from your Twitch Stream', 'tp-ttvw' ), ) // Args
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

            $api_key = $instance['api_key'];
            $hide_offline_channels = $instance['hide_offline_channels'];
            $template = $instance['template'];
            $style = " " . $instance['style'];

            $twitch_game = $instance['twitch_game'];
            $max_games = $instance['max_games'];
            $channels_game = $instance['channels_game'];
            @$twitch_streamer_language = $instance['twitch_streamer_language'];


            // Data for output
            $channels_data = array();


            // Get channels from user
            $channels = str_replace(' ', '', $instance['channel']);
            $channels = explode(",", $channels );


            // --------- IF ABFRAGE ---- START ------------- //

            if ( $channels_game == 'channels' )
            {
                foreach ( $channels as $channel)
                {
                    $channels_data[] = tp_get_channel_data ( $api_key, $channel );
                } }
            else {
                $channels_data = tp_get_channels_game_data( $api_key, $twitch_game, $max_games, $twitch_streamer_language );
            }



            //  IF abfrage ---- IF NOT LEER
            //  foreach ( $channels as $channel ) {
            //     $channels_data[] = tp_get_channel_data( $api_key, $channel );
            //}

            // $channels_data = tp_get_channels_game_data( $api_key, $twitch_game, $max_games );

            // --------- IF ABFRAGE ---- ENDE ------------- //

            // if offline dont show
            if ( $hide_offline_channels == '1') {
                foreach ( $channels_data as $key => $channel) {
                    if ( $channel['live'] != 1 ) {
                        unset($channels_data[$key]);
                    }
                }
            }

            // Sort by Viewers ( IS ARRAY )
            if ( is_array($channels_data) && sizeof ( $channels_data ) > 0 ) {
                foreach ($channels_data as $key => $row) {
                    @$viewers[$key] = $row['viewers'];
                }

                array_multisort($viewers, SORT_DESC, $channels_data);

            }

            // Dont show View if 0 Channels + mix big box online with small offline box
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
        $template = ! empty( $instance['template'] ) ? $instance['template'] : '';
        $style = ! empty( $instance['style'] ) ? $instance['style'] : '';

        $twitch_game = ! empty( $instance['twitch_game'] ) ? $instance['twitch_game'] : '';
        $max_games = ! empty( $instance['max_games'] ) ? $instance['max_games'] : '';
        $channels_game = ! empty( $instance['channels_game'] ) ? $instance['channels_game'] : '';
        $twitch_streamer_language = ! empty( $instance['twitch_streamer_language'] ) ? $instance['twitch_streamer_language'] : '';

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e( 'Twitch API Key:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'api_key' ); ?>" name="<?php echo $this->get_field_name( 'api_key' ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'channels_game' ); ?>"><?php _e( 'Channels or Game:', 'tp-ttvw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'channels_game' ); ?>" name="<?php echo $this->get_field_name( 'channels_game' ); ?>">
                <option value="channels"<?php selected( $channels_game, "Channels" ); ?>><?php _e( 'Channels', 'tp-ttvw' ); ?></option>
                <option value="game"<?php selected( $channels_game, "Game" ); ?>><?php _e( 'Game', 'tp-ttvw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'channel' ); ?>"><?php _e( 'Twitch Channels:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'channel' ); ?>" name="<?php echo $this->get_field_name( 'channel' ); ?>" type="text" value="<?php echo esc_attr( $channel ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'twitch_game' ); ?>"><?php _e( 'Twitch Game:', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitch_game' ); ?>" name="<?php echo $this->get_field_name( 'twitch_game' ); ?>" type="text" value="<?php echo esc_attr( $twitch_game ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'max_games' ); ?>"><?php _e( 'Max Streams (1-100):', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'max_games' ); ?>" name="<?php echo $this->get_field_name( 'max_games' ); ?>" type="text" value="<?php echo esc_attr( $max_games ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'twitch_streamer_language' ); ?>"><?php _e( 'Twitch Streamer Language (1-100):', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'twitch_streamer_language' ); ?>" name="<?php echo $this->get_field_name( 'twitch_streamer_language' ); ?>" type="text" value="<?php echo esc_attr( $twitch_streamer_language ); ?>">
        </p>

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
            <label for="<?php echo $this->get_field_id( 'hide_offline_channels' ); ?>"><?php _e( 'Hide Offline User', 'tp-ttvw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'hide_offline_channels' ); ?>" name="<?php echo $this->get_field_name( 'hide_offline_channels' ); ?>" type="checkbox" value="1" <?php echo($instance['hide_offline_channels'] == 1 ? 'checked' : ''); ?>  >
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
        $instance['template'] = ( ! empty( $new_instance['template'] ) ) ? strip_tags( $new_instance['template'] ) : 'widget_small';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : 'light';

        $instance['twitch_game'] = ( ! empty( $new_instance['twitch_game'] ) ) ? strip_tags( $new_instance['twitch_game'] ) : '';
        $instance['max_games'] = ( ! empty( $new_instance['max_games'] ) ) ? strip_tags( $new_instance['max_games'] ) : '';
        $instance['channels_game'] = ( ! empty( $new_instance['channels_game'] ) ) ? strip_tags( $new_instance['channels_game'] ) : '';
        $instance['twitch_streamer_language'] = ( ! empty( $new_instance['twitch_streamer_language'] ) ) ? strip_tags( $new_instance['twitch_streamer_language'] ) : '';

        return $instance;
    }

} // class Foo_Widget


// register TP Twitch widget
function tp_ttvw_register_widget() {
    register_widget( 'TP_TTVW_Widget' );
}
add_action( 'widgets_init', 'tp_ttvw_register_widget' );