<?php
/**
 * Helpers
 *
 * @link       https://github.com/misfist/site-functionality
 * @since      1.0.0
 *
 * @package    site-functionality
 */

namespace Site_Functionality;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get repeater field values
 *
 * @param  int                      $post_id
 * @param  string                   $field_name
 * @param  mixed string || string[] $subfield_names
 * @return array
 */
function get_post_repeater_values( $post_id = null, string $field_name, $subfield_names ): array {
	global $post;
	$post_id        = ( $post_id ) ? (int) $post_id : get_the_ID();
	$repeater_value = get_post_meta( $post_id, $field_name, true );
	$array          = array();
	if ( $repeater_value ) {
		for ( $i = 0; $i < $repeater_value; $i++ ) {
			if ( 'array' === gettype( $subfield_names ) ) {
				foreach ( $subfield_names as $subfield_name ) {
					$sub_field_value               = get_post_repeater_value( $post_id, $field_name, $subfield_name, $i );
					$array[ $i ][ $subfield_name ] = $sub_field_value;
				}
			} elseif ( 'string' === gettype( $subfield_names ) ) {
				$sub_field_value                = get_post_repeater_value( $post_id, $field_name, $subfield_names, $i );
				$array[ $i ][ $subfield_names ] = $sub_field_value;
			}
		}
	}
	return $array;
}

/**
 * Get repeater field value
 *
 * @param  integer $post_id
 * @param  string  $field_name
 * @param  string  $subfield_name
 * @param  integer $index
 * @return void
 */
function get_post_repeater_value( int $post_id, string $field_name, string $subfield_name, int $index ) {
	$meta_key        = "{$field_name}_{$index}_{$subfield_name}";
	$sub_field_value = get_post_meta( $post_id, $meta_key, true );
	return $sub_field_value;
}

/**
 * Get repeater field values
 *
 * @param  int                      $post_id
 * @param  string                   $field_name
 * @param  mixed string || string[] $subfield_names
 * @return array
 */
function get_term_repeater_values( $term_id, string $field_name, $subfield_names ): array {
	$repeater_value = get_term_meta( $term_id, $field_name, true );
	$array          = array();
	if ( $repeater_value ) {
		for ( $i = 0; $i < $repeater_value; $i++ ) {
			if ( 'array' === gettype( $subfield_names ) ) {
				foreach ( $subfield_names as $subfield_name ) {
					$sub_field_value               = get_term_repeater_value( $term_id, $field_name, $subfield_name, $i );
					$array[ $i ][ $subfield_name ] = $sub_field_value;
				}
			} elseif ( 'string' === gettype( $subfield_names ) ) {
				$sub_field_value                = get_term_repeater_value( $term_id, $field_name, $subfield_names, $i );
				$array[ $i ][ $subfield_names ] = $sub_field_value;
			}
		}
	}
	return $array;
}

/**
 * Get repeater field value
 *
 * @param  integer $post_id
 * @param  string  $field_name
 * @param  string  $subfield_name
 * @param  integer $index
 * @return void
 */
function get_term_repeater_value( int $term_id, string $field_name, string $subfield_name, int $index ) {
	$meta_key        = "{$field_name}_{$index}_{$subfield_name}";
	$sub_field_value = get_term_meta( $term_id, $meta_key, true );
	return $sub_field_value;
}
