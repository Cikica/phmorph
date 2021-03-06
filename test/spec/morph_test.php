<?php

	class morph_test extends PHPUnit_Framework_TestCase
	{
		public function test_key_value_loop ()
		{
			$this->assertEquals(
				array(
					'02s' => 'd04',
					'12b' => 'some14'
				),
				morph::key_value_loop(array(
					'subject' => array(
						's' => 'd',
						'b' => 'some'
					),
					'else_do' => function ( $loop ) { 
						return array(
							'key'   => "{$loop['index']}2{$loop['key']}",
							'value' => "{$loop['value']}{$loop['index']}4"
						);
					}
				))
			);
		}

		public function test_get_value_of_nested_array ()
		{
			$this->assertEquals(
				array( "some" => "some" ),
				morph::get_value_of_nested_array(array(
					'route' => array( 1, 2, 3 ),
					'by'    => 'index',
					'array' => array(
						'some' => array(
							's' => 's',
							'd' => array(
								's' => 's',
								'd' => 'd',
								'c' => array(
									"some" => "some"
								)
							)
						)
					)
				))
			);

			$this->assertEquals(
				array( "some" => "some" ),
				morph::get_value_of_nested_array(array(
					'route' => array( "some", "d", "c" ),
					'by'    => 'key',
					'array' => array(
						'some' => array(
							's' => 's',
							'd' => array(
								's' => 's',
								'd' => 'd',
								'c' => array(
									"some" => "some"
								)
							)
						)
					)
				))
			);

			$this->assertEquals(
				array( "some" => "some" ),
				morph::get_value_of_nested_array(array(
					'route' => array(),
					'by'    => 'index',
					'array' => array(
						"some" => "some"
					)
				))
			);
		}

		public function test_set_value_of_nested_array ()
		{	
			$this->assertEquals(
				array(
					"s" => array(
						"d" => "some",
						"c" => "some"
					)
				),
				morph::set_value_of_nested_array(array(
					'value'  => array(
						"s" => array(
							"d" => "some",
							"c" => "s"
						)
					),
					'route'  => array( "s", "c" ),
					'change' => "some"
				))
			);

			$this->assertEquals(
				array(
					"s" => array(
						"d" => "some",
						"c" => "s",
						"g" => "new"
					)
				),
				morph::set_value_of_nested_array(array(
					'value'  => array(
						"s" => array(
							"d" => "some",
							"c" => "s"
						)
					),
					'route'  => array( "s", "g" ),
					'change' => "new"
				))
			);
		}
	}