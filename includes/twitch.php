<?php

function tp_debug($args)
{
    echo '<pre>';
    print_r($args);
    echo '</pre>';
}


function get_channel_data($channel)
{

    // Get cache
    $cache = get_transient( TP_TTVW_CACHE . $channel );

    if (!empty ($cache) || WP_DEBUG)  {
        return $cache;
    }

    $data = array('username' => $channel);

    // Get basic data
    $basic = tp_ttvw_get_data('https://api.twitch.tv/kraken/channels/' . $channel);

    $data['display_name'] = $basic['display_name'];
    $data['broadcaster_language'] = $basic['broadcaster_language'];
    $data['channel_url'] = $basic['url'];
    $data['views'] = $basic['views'];
    $data['followers'] = $basic['followers'];
    $data['logo'] = $basic['logo'];

    // Get live data
    $live = tp_ttvw_get_data('https://api.twitch.tv/kraken/streams/' . $channel);

    if (!empty ($live['stream'])) {
        $data['live'] = true;
        $data['viewers'] = $live['stream']['viewers'];
        $data['preview'] = $live['stream']['preview']['small'];
        $data['preview_medium'] = $live['stream']['preview']['medium'];
        $data['preview_large'] = $live['stream']['preview']['large'];
        $data['aktiv_game'] = $live['stream']['channel']['game'];
        $data['channel_title'] = $live['stream']['channel']['status'];

    } else {
        $data['live'] = false;
    }

    // Logic
    if (!empty ($data['preview'])) {
        $data['image'] = $data['preview'];
    } elseif (!empty ($data['logo'])) {
        $data['image'] = $data['logo'];
    } else {
        $data['image'] = TP_TTVW_URL . 'assets/img/twitch-logo-45x45.png';
    }

    // Save cache
    $options = get_option('tp_ttvw');
    $cache_duration = (isset ($options['cache_duration'])) ? intval($options['cache_duration']) : 600;
    $transient_key = TP_TTVW_CACHE . $channel;
    set_transient( $transient_key, $data, $cache_duration );

    // Get the current list of transients.
    $transient_keys = get_option( 'tp_ttvw_cache_transient_keys', array() );

    // Appent new transient key
    $transient_keys[] = $transient_key;

    // Update the list of transients
    update_option( 'tp_ttvw_cache_transient_keys', $transient_keys );

    return $data;
}