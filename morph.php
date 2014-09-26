<?php

/**
* The php version of the morph library
*/
class morph
{
	static function get_value_of_nested_array ( $get )
	{

		if ( count( $get['route'] ) > 0 ) {

			if ( $get['by'] == 'index' ) {
				$array_keys = array_keys( $get['array'] );
				$key_name   = $array_keys[( $get['route'][0] - 1 )];
			}

			if ( $get['by'] == 'key' ) {
				$key_name = $get['route'][0];
			}

			return morph::get_value_of_nested_array(array(
				'route' => array_slice( $get['route'], 1 ),
				'array' => $get['array'][$key_name],
				'by'    => $get['by']
			));

		} else { 
			return $get['array'];
		}
	}

	static function set_value_of_nested_array ( $get )
	{	
		if ( count( $get['route'] ) == 1 ) {
			$get['value'][$get['route'][0]] = $get['change'];
		} else { 
			$get['value'][$get['route'][0]] = morph::set_value_of_nested_array(array(
				'value'  => ( 
					array_key_exists( $get['route'][0], $get['value'] ) ? 
					$get['value'][$get['route'][0]] : 
					array()
				),
				'route'  => array_slice( $get['route'], 1 ),
				'change' => $get['change']
			));
		}

		return $get['value'];
	}

	static function index_loop ( $what )
	{
		return toolshed::base_loop(array(
			'subject'      => $what['subject'],
			'into'         => ( isset( $what['into'] ) ? $what['into'] : array() ),
			'start_at'     => 0,
			'is_done_when' => function ( $loop ) {
				return ( count( $loop['subject'] ) == $loop['start_at'] );
			},
			'if_done'      => function ( $loop ) use ( $what ) {
				if ( isset( $what['if_done?'] ) ) { 
					return call_user_func( $what['if_done?'], $loop );
				} else { 
					return $loop['into'];
				}
			},
			'else_do'      => function ( $loop ) use ( $what ) {
				return array(
					'subject'      => $loop['subject'],
					'start_at'     => $loop['start_at'] + 1,
					'is_done_when' => $loop['is_done_when'],
					'into'     => call_user_func( $what['else_do'], array(
						'subject' => $loop['subject'],
						'index'   => $loop['start_at'],
						'into'    => $loop['into'],
						'indexed' => $loop['subject'][$loop['start_at']],
					)),
					'if_done'  => $loop['if_done'],
					'else_do'  => $loop['else_do']
				);
			}
		));	
	}

	static function base_loop ( $loop )
	{	
		if ( call_user_func( $loop['is_done_when'], $loop ) ) { 
			return call_user_func( $loop['if_done'], $loop );
		} else { 
			return toolshed::base_loop( call_user_func( $loop['else_do'], $loop ) );
		}
	}
}