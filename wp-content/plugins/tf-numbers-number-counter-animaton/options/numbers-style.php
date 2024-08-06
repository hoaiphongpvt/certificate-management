<?php
namespace TFNumbersOptions;

class NumbersStyle implements \TFNumbersOpsInterface {

	public function init( $prefix ) {
		$section = $this->get_section( $prefix );
		$options = $this->get_options();

		foreach ( $options as $values => $option ) {
			$option['id'] = $prefix . $option['id'];
			$section->add_field( $option );
		}
	}

	public function get_section( $prefix ) {
		$section = new_cmb2_box(
            array(
				'id'           => $prefix . 'stats_stle',
				'context'      => 'normal',
				'priority'     => 'core',
				'title'        => esc_html__( 'Numbers Style', 'tf_numbers' ),
				'object_types' => array( 'tf_stats' ),
            )
		);

		return $section;
	}

	public function get_options() {
		$options = array(
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Icons Color', 'tf_numbers' ),
				'id'   => 'ic',
				'type' => 'colorpicker',
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Numbers Color', 'tf_numbers' ),
				'id'   => 'nc',
				'type' => 'colorpicker',
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Numbers Title Color', 'tf_numbers' ),
				'id'   => 'ctc',
				'type' => 'colorpicker',
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Numbers Size', 'tf_numbers' ),
				'id'   => 'nbs',
				'type' => 'text',
				'desc' => esc_html__( 'Add value that will be applied to numbers size. Value will be applied in em.', 'tf_numbers' ),
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Titles Size', 'tf_numbers' ),
				'id'   => 'tts',
				'type' => 'text',
				'desc' => esc_html__( 'Add value that will be applied to titles size. Value will be applied in em.', 'tf_numbers' ),
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Icons Size', 'tf_numbers' ),
				'id'   => 'ics',
				'type' => 'text',
				'desc' => esc_html__( 'Add value that will be applied to icons size. Value will be applied in em.', 'tf_numbers' ),
			),
			array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Section Border(in px)', 'tf_numbers' ),
				'id'   => 'border',
				'type' => 'text',
				'desc' => esc_html__( 'Specify the border width in pixels.', 'tf_numbers' ),
			),
                        
                        array(
                                        'name'    => '<span class="dashicons dashicons-star-empty"></span> ' . esc_html__( 'Border Style', 'tf_numbers' ),
                                        'id'      => 'border_type',
                                        'type'    => 'select',
                                        'desc' => esc_html__( 'Select the preferred type of the border.', 'tf_numbers' ),
                                        'options' => apply_filters(
                            'tf_numbers_dynamic_options',
                            array(
                                        'none'   => esc_html__( 'None', 'tf_numbers' ),
					'dashed'    => esc_html__( 'Dashed', 'tf_numbers' ),
					'dotted' => esc_html__( 'Dotted', 'tf_numbers' ),
                                        'solid'   => esc_html__( 'Solid', 'tf_numbers' ),
					'double'    => esc_html__( 'Double', 'tf_numbers' ),
					'groove' => esc_html__( 'Groove', 'tf_numbers' ),
                                        'ridge'   => esc_html__( 'Ridge', 'tf_numbers' ),
					'inset'    => esc_html__( 'Inset', 'tf_numbers' ),
					'outset' => esc_html__( 'Outset', 'tf_numbers' ),
                                        'hidden' => esc_html__( 'Hidden', 'tf_numbers' ),
                            )
                                                
                        ),
                                ),
                        array(
				'name' => '<span class="dashicons dashicons-edit"></span> ' . esc_html__( 'Border Color', 'tf_numbers' ),
				'id'   => 'boc',
				'type' => 'colorpicker',
                                'desc' => esc_html__( 'Select the color for the border.', 'tf_numbers' ),
			),
                        
                        
		);

		return $options;
	}
}
