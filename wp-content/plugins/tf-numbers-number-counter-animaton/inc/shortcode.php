<?php

if ( !class_exists( 'TF_Numbers_Shortcode' ) ) {
	class TF_Numbers_Shortcode {

		// global style that will be
		// colleted in this variable
		private $css = array();
		// global index that will be
		//used in styles array
		private $n;

		public function __construct() {
			add_shortcode( 'tf_numbers', array( $this, 'tf_numbers_shortcode' ) );
                        add_action('publish_tf_stats', array( $this, 'tf_publish_post_publish_version_number' ),10,2);
			add_action( 'wp_footer', array( $this, 'tf_print_style' ) );
                        add_action( 'post_updated', array( $this, 'tf_publish_post_update_version_number' ), 10, 3 ); 

		}
                
                public function tf_publish_post_update_version_number($post_ID, $post_after, $post_before){
                    
                    if($post_after->post_type=='tf_stats')
                    {
                        $value = get_option('tf_footer_style_version', 1000);
                        $value = $value + 1;
                        update_option('tf_footer_style_version', $value);
                    }
                }
                
                public function tf_publish_post_publish_version_number($ID , $post ){
                    
                    if($post->post_type=='tf_stats')
                    {
                        $value = get_option('tf_footer_style_version', 1000);
                        $value = $value + 1;
                        update_option('tf_footer_style_version', $value);
                    }
                }
                
		public function tf_numbers_shortcode( $atts ) {
			/**
			  * Call post by name extracting the $name
			  * from the shortcode previously created
			  * in custom post column
			  */

                        $default_attributes = array(
							'tf_numbers' => '',
							'name'       => '',
						);
						$attributes         = shortcode_atts( $default_attributes, $atts );
                        $name               = $attributes['name'];
                        $tf_numbers         = $attributes['tf_numbers'];
						$args               = array(
							'post_type' => 'tf_stats',
							'name'      => $name,
						);
						$numbers            = get_posts( $args );
						$html               = '';
						if ( $numbers ) {
							foreach ( $numbers as $number ) {
								setup_postdata( $number );
								$ID    = 'tf-stat-' . $number->ID;
								$vals  = get_post_meta( $number->ID, '_tf_stat', true );
								$image = get_post_meta( $number->ID, '_tf_bg', true );
								$bgc   = get_post_meta( $number->ID, '_tf_bgc', true );
								$bgct  = get_post_meta( $number->ID, '_tf_bgct', true );
								if ( !$image ) {
									$image = esc_url( $bgc );
								} else {
									$image = 'url(' . esc_url( $image ) . ')';
								}
								$user_style  = '';
								$addon_style = '';
								$tc          = get_post_meta( $number->ID, '_tf_tc', true );
								$ic          = get_post_meta( $number->ID, '_tf_ic', true );
								$ctc         = get_post_meta( $number->ID, '_tf_ctc', true );
								$nc          = get_post_meta( $number->ID, '_tf_nc', true );
								$ics         = get_post_meta( $number->ID, '_tf_ics', true );
								$border_type      = get_post_meta( $number->ID, '_tf_border_type', true );
                                                                $border_color =  get_post_meta( $number->ID, '_tf_boc', true );
                                                                $border_size =  get_post_meta( $number->ID, '_tf_border', true );
								$tts         = get_post_meta( $number->ID, '_tf_tts', true );
								$nbs         = get_post_meta( $number->ID, '_tf_nbs', true );
								$layout      = get_post_meta( $number->ID, '_tf_layout', true );
								$nmh         = get_post_meta( $number->ID, '_tf_nmh', true ) ? get_post_meta( $number->ID, '_tf_nmh', true ) : '';
								$sp          = get_post_meta( $number->ID, '_tf_sp', true ) ? get_post_meta( $number->ID, '_tf_sp', true ) : 10;
								$nmt         = get_post_meta( $number->ID, '_tf_nmt', true ) ? 'data-nmt="' . get_post_meta( $number->ID, '_tf_nmt', true ) . '"' : '';
								$nmtd        = get_post_meta( $number->ID, '_tf_nmtd', true ) ? ' data-nmtd="' . get_post_meta( $number->ID, '_tf_nmtd', true ) . '"' : '';
								$nma         = get_post_meta( $number->ID, '_tf_nma', true ) ? 'data-nma="' . get_post_meta( $number->ID, '_tf_nma', true ) . '"' : '';
								$nmad        = get_post_meta( $number->ID, '_tf_nmad', true ) ? ' data-nmad="' . get_post_meta( $number->ID, '_tf_nmad', true ) . '"' : '';
								$cm          = get_post_meta( $number->ID, '_tf_cm', true ) ? 'data-cm="' . get_post_meta( $number->ID, '_tf_cm', true ) . '"' : '';
								$cmo         = get_post_meta( $number->ID, '_tf_cmo', true ) ? 'data-cmo="' . get_post_meta( $number->ID, '_tf_cmo', true ) . '"' : '';
								$tvm         = get_post_meta( $number->ID, '_tf_tvm', true ) ? get_post_meta( $number->ID, '_tf_tvm', true ) : '';
								$stats       = $this->apply_layout( $number->ID );
								$atts        = apply_filters( 'tf_numbers_atts', '', $number->ID );

								//css
								$css = '#' . esc_attr( $ID ) . '{background: ' . esc_attr( $image ) . '; background-size: cover} @media only screen and (max-width: 860px){ #' . esc_attr( $ID ) . '{background-size: cover} }';
								if ( strpos( $image, 'url' ) !== false ) {
									//$css .= '#' . esc_attr( $ID ) . ':after{content: " ";display: block;background: rgba(0,0,0,0.57);width: 100%;height: 100%;position: absolute;top:0;left:0}';
                                                                        $css .= '#' . esc_attr( $ID ) . ':after{content: " ";display: block;width: 100%;height: 100%;position: absolute;top:0;left:0}';
								}
								if ( 'on' == $nmh ) {
                                                                   $op = 0;
                                                                   if( defined( 'TF_BUNDLE_NAME' ) ) {
                                                                            $op = 0;
                                                                        } 
                                                                        else 
                                                                        {
                                                                            $op = 1;
                                                                        }
                                                                   
									$css .= '#' . esc_attr( $ID ) . ' .stat, #' . esc_attr( $ID ) . '{opacity: '. $op .'}';
									$css .= '#' . esc_attr( $ID ) . ' .stat[data-nm="none"], #' . esc_attr( $ID ) . '[data-nma="none"]{opacity: 1}';
								}
								if ( 'on' == $bgct ) {
									$css .= '#' . esc_attr( $ID ) . '{background: transparent} #' . esc_attr( $ID ) . ':after{display: none}';
								}
								if ( ! empty( $border_size ) && ! empty( $border_color ) &&  ! empty( $border_type ) ) {
									$css .= '#' . esc_attr( $ID ) . '{border: ' . esc_attr( $border_size ) . 'px ' . esc_attr( $border_type ) . ' ' . esc_attr( $border_color ) . '}'; 
								}
								$css .= '#' . esc_attr( $ID ) . ' .stat .fa{color: ' . esc_attr( $ic ) . '; font-size: ' . esc_attr( $ics ) . 'em} ';
								$css .= '#' . esc_attr( $ID ) . ' .stat .number{color: ' . esc_attr( $nc ) . '; font-size: ' . esc_attr( $nbs ) . 'em} ';
								$css .= '#' . esc_attr( $ID ) . ' .stat .count-title{color: ' . esc_attr( $ctc ) . '; font-size: ' . esc_attr( $tts ) . 'em; margin-bottom: 0} .stat .count-subtitle{display: block;}';
								$css .= '#' . esc_attr( $ID ) . ' h3{color: ' . esc_attr( $tc ) . '; margin: ' . esc_attr( (int)$tvm ) . 'em 0;}';

								$user_style = apply_filters( 'tf_custom_styles', $user_style );
								if ( $user_style ) {

									foreach ( $user_style as $style ) {
										$selector = $style['selector'];
										$values   = $style['values'];
										$css     .= '#' . esc_attr( $ID ) . ' ' . esc_attr( $selector ) . '{';

										foreach ( $values as $value ) {
											  $val  = get_post_meta( $number->ID, '_tf_' . esc_attr( $value['id'] ), true );
											  $prop = $value['property'];
											  $css .= esc_attr( $prop ) . ':' . esc_attr( $val ) . ';';
										}

										$css .= '}';
									}
								}

								$css .= apply_filters( 'tf_numbers_after_style', '', $ID, $number->ID );

								$addon_style = apply_filters( 'tf_addon_styles', $addon_style );
								if ( $addon_style ) {
									foreach ( $addon_style as $style ) {
										$selector = $style['selector'];
										$values   = $style['values'];
										$css     .= '#' . esc_attr( $ID ) . ' ' . esc_attr( $selector ) . '{';

										foreach ( $values as $value ) {
											  $val  = get_post_meta( $number->ID, '_tf_' . esc_attr( $value['id'] ), true );
											  $prop = $value['property'];
											  $css .= esc_attr( $prop ) . ':' . esc_attr( $val ) . ';';
										}

										$css .= '}';
									}
								}

								$this->css[ $ID ] = $css;

								$html .= '<section id="' . esc_attr( $ID ) . '" class="statistics ' . esc_attr( $layout ) . '" ' . $nma . $nmad . $cmo . ' data-sp="' . $sp  . '" ' . $cm . $atts . '>';

								if ( isset( $number->post_title ) && $number->post_title ) {
									$html .='<h3 ' . $nmt . $nmtd . '>' . apply_filters( 'the_title', $number->post_title ) . '</h3>';
								}

								$html .= '<div class="statistics-inner">';
								if ( is_array( $vals ) || is_object( $vals ) ) {
									foreach ( $vals as $key => $value ) {
										$nm      = isset( $value['_tf_nm'] ) ? 'data-nm="' . esc_attr( $value['_tf_nm'] ) . '"' : '';
										$nd      = isset( $value['_tf_nd'] ) ? ' data-nd="' . esc_attr( $value['_tf_nd'] ) . '"' : '';
										$nl      = isset( $value['_tf_nl'] ) ? ' data-nl="' . esc_attr( $value['_tf_nl'] ) . '"' : '';
										$tfnumber  = isset( $value['_tf_number'] ) ? $value['_tf_number'] : 0;
										$dynamic = isset( $value['_tf_dynamic_nmb'] ) ? $value['_tf_dynamic_nmb'] : 0;
										$number_orignal  = $this->get_number( $tfnumber, $dynamic );
                                                                                $number = filter_var($number_orignal, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
										$attts   = apply_filters( 'tf_numbers_number_data', '', $value );
                                                                                //echo $number;die;
										$html   .= sprintf( '<div class="stat" %s data-count="%s" data-orignal_count="%s">', $nm . $nd . $nl . $attts, esc_attr( $number ),esc_attr( $tfnumber ) );
										$html   .= $this->list_stats( $stats, $value,$ics,$sp  );
										$html   .= '</div>';
									}
								}
								$html .= '</div></section>';
							}
						}

						return $html;
		}

		public function list_stats( $stats, $value, $icon_size,$sp ) {
                   //print_r($value);
			//elements
			$cs   = isset( $value['cr'] ) ? ' ' . $value['cr'] : '';
			$cs  .= isset( $value['crp'] ) ? ' ' . $value['crp'] : '';
                        
                        
			$icon = '<span class="' . ( isset( $value['_tf_icon'] ) ? esc_attr( $value['_tf_icon'] ) : '' ) . '"></span>';
			if ( isset( $value['_tf_icon'] ) && strpos( $value['_tf_icon'], '.' ) !== false ) {
				$icon = '<span class="custom-icon"><img src="' . esc_attr( $value['_tf_icon'] ) . '" class="cusom-icon-img" alt="icon"  /></span>';
			}
                        
			$number = '<span class="number' . $cs . '" ></span>';
                        //$number = '<span class="number' . $cs . '">' . $value['_tf_number'] . '</span>';
                        $title = '';
                        if( !empty( $value['_tf_title'] ) )
                        {
                            $title = $value['_tf_title'];          
                        }
                                
                        $title  = '<span class="count-title">' . esc_html( $title ) . '</span>';
			$sub    = '';

			if ( isset( $value['_tf_subt'] ) ) {
				$sub = '<span class="count-subtitle">' . esc_html( $value['_tf_subt'] ) . '</span>';
			}
                        $stats_count = count( $stats );
			for ( $g = 0; $g < $stats_count; $g++ ) {

				if ( strpos( $stats[ $g ], '[val]' ) !== false ) {
					$split = explode( '[val]', $stats[ $g ] );
					$val   = $split[1];

					if ( isset( $value[ $val ] ) ) {
						  $stats[ $g ] = $split[0] . $value[ $val ] . $split[2];
					} else {
						$stats[ $g ] = '';
					}
				}

				if ( $stats[ $g ] === 'icon' ) {
					$stats[ $g ] = $icon;
				}
				if ( $stats[ $g ] === 'number' ) {
					$stats[ $g ] = $number;
				}
				if ( $stats[ $g ] === 'title' ) {
					$stats[ $g ] = $title;
				}
				if ( $stats[ $g ] === 'sub' ) {
					$stats[ $g ] = $sub;
				}
			}

			$html = '';
			foreach ( $stats as $stat ) {
				$html .= $stat;
			}

			return $html;
		}
                
		public function get_number( $number, $dynamic ) { 
			if ( $dynamic && $number) {
				if ( method_exists( $this, "get_{$dynamic}_count" ) ) { 
					$method = "get_{$dynamic}_count";
					return $this->$method();

				} else {
					return apply_filters( 'tf_numbers_dynamic_number', $dynamic );
				}
			} else {
						return $number;
			}
		}

		public function apply_layout( $id ) {
			$layout = get_post_meta( $id, '_tf_layout', true );
			if ( 'n2' === $layout || 'n4' === $layout ) {
				$order = array(
					0 => 'icon',
					1 => 'title',
					2 => 'number',
				);
			} elseif ( 'n6' === $layout ) {
				$order =  array(
					0 => 'number',
					1 => 'icon',
					2 => 'title',
				);
			} elseif ( 'n7' === $layout ) {
				$order =  array(
					1 => 'icon',
					2 => 'number',
					0 => 'title',
					3 => 'sub',
				);
			} elseif ( 'n8' === $layout ) {
				$order =  array(
					0 => 'number',
					1 => 'title',
					2 => 'sub',
				);
			} elseif ( 'n9' === $layout ) {
				$order =  array(
					1 => 'title',
					0 => 'number',
					2 => 'sub',
				);
			} else {
				  $order =  array(
					  0 => 'icon',
					  1 => 'number',
					  2 => 'title',
				  );
			}

			return apply_filters( 'tf_layouts_order', $order );
		}

		public function tf_print_style() {
                        $merged_file_location = plugin_dir_path( __FILE__ ) . 'tf-footer-style.css';
			$styles                           = $this->css;
			$css                              = '';
			foreach ( $styles as $style ) {
				$css .= $style;
			}
                        $version = get_option('tf_footer_style_version', 1000);
			file_put_contents( $merged_file_location, $css );
			wp_enqueue_style( 'tf-footer-style', plugin_dir_url( __FILE__ ) . 'tf-footer-style.css', array(), $version, 'all' );

		}

		public function get_articles_count() {
			$args  = array(
				'posts_per_page'   => -1,
				'post_type'        => array('post'),
				'post_status'      => 'publish',
				'suppress_filters' => true,
			);
			$posts = get_posts( $args );

			return count( $posts );
		}

		public function get_authors_count() {
			//$user_query = new \WP_User_Query( array( 'who' => 'authors' ) );
                        $user_query  = new WP_User_Query( array( 'role' => 'Author' ) ); 
                        $users_count = (int) $user_query->get_total();
                        //$users = get_users( [ 'role__in' => [ 'Author', 'Administrator', 'Editor', 'Contributor'] ] );                
                        return $users_count;
		}

		public function get_categories_count() {
			$args       = array( 'hide_empty' => 0 );
			$categories = get_categories( $args );

			return count( $categories );
		}

		public function get_comments_count() {
			$comments_count = wp_count_comments();

			return $comments_count->total_comments;
			// echo "Comments for site <br />";
			// echo "Comments in moderation: " . $comments_count->moderated . "<br />";
			// echo "Comments approved: " . $comments_count->approved . "<br />";
			// echo "Comments in Spam: " . $comments_count->spam . "<br />";
			// echo "Comments in Trash: " . $comments_count->trash . "<br />";
			// echo "Total Comments: " . $comments_count->total_comments . "<br />";
		}
	}//class ends
}//if !class_exists
