<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Alpha_SP extends Widget_Base {

    public function get_name() {
		return 'alpha-sp';
	}

	public function get_title() {
		return __( 'Alpha SP Widget', 'alpha-single-product-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-single-product';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
	}

    protected function _register_controls() {

        $this->start_controls_section(
            'alpha-sp-products',
            [
                'label' => esc_html__( 'Query Settings', 'alpha-single-product-for-elementor' ),
            ]
        );

        $this->add_control(
            'alphasp_product_id',
            [
                'label' => __( 'Select Product', 'alpha-single-product-for-elementor' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $this->alphasp_post_name( 'product' ),
            ]
        );

        $this->end_controls_section();

        // Product Content
        $this->start_controls_section(
            'alpha-sp-product-setting',
            [
                'label' => esc_html__( 'Product Settings', 'alpha-single-product-for-elementor' ),
            ]
        ); 
            $this->add_control(
                'hide_product_title',
                [
                    'label'     => __( 'Hide Title', 'alpha-single-product-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-info .sp-product-title' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'hide_product_price',
                [
                    'label'     => __( 'Hide Price', 'alpha-single-product-for-elementor' ),
                    'type'      => Controls_Manager::SWITCHER,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-info .sp-product-price' => 'display: none;',
                    ],
                ]
            );

            $this->add_control(
                'product_action_button_text',
                [
                    'label' => __( 'Action Button Text', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'ADD TO CART', 'alpha-single-product-for-elementor' ),
                ]
            );

            $this->add_control(
                'product_action_button_class',
                [
                    'label' => __( 'Add CSS Class', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::TEXT,
                ]
            );

            $this->add_control(
                'product_image_size',
                [
                    'label' => __( 'Product Image Size', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'product_image_size_width',
                [
                    'label' => __( 'Width', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::TEXT,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-image a img' => 'width: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_size_height',
                [
                    'label' => __( 'Height', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::TEXT,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-image a img' => 'height: {{VALUE}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Style Default tab section
        $this->start_controls_section(
            'alpha_sp_product_style_section',
            [
                'label' => __( 'Style', 'alpha-single-product-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'product_padding',
                [
                    'label' => __( 'Padding', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_margin',
                [
                    'label' => __( 'Margin', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            
            // Product Title
            $this->add_control(
                'product_title_heading',
                [
                    'label' => __( 'Product Title', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_title_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .sp-product-title a',
                ]
            );

            $this->add_control(
                'product_title_color',
                [
                    'label' => __( 'Title Color', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>  '#fff' ,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-title a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'product_title_hover_color',
                [
                    'label' => __( 'Title Hover Color', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#FFD100' ,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-title a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            // Product Price
            $this->add_control(
                'product_price_heading',
                [
                    'label' => __( 'Product Price', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'product_price_color',
                [
                    'label' => __( 'Price Color', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'default' =>'#fff' ,
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-price span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'product_price_typography',
                    'scheme' => Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .sp-product-price span',
                ]
            );

            $this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'alpha-single-product-for-elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .sp-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section(); // Style Default End

        // Style Action Button tab section
        $this->start_controls_section(
            'alpha_sp_product_action_button_style_section',
            [
                'label' => __( 'Action Button Style', 'alpha-single-product-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'heading_footer_button',
			[
				'label' => __( 'Button', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'alpha-single-product-for-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .sp-cart-button a',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .sp-cart-button a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => __( 'Button Padding', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'button_text_margin',
			[
				'label' => __( 'Button Margin', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'alpha-single-product-for-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'alpha-single-product-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sp-cart-button a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

    }

    /**
     * Get Post List
     * return array
     */
    function alphasp_post_name( $post_type = 'post' ){
    $options = array();
    $options['0'] = __('Select','alpha-single-product-for-elementor');
    $perpage = 7;
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
    }

    function add_to_cart_text() {
        $product_action_button_text = $this->get_settings('product_action_button_text' );
        return __( $product_action_button_text, 'alpha-single-product-for-elementor' ); 
    }

    protected function render( $instance = [] ) {

        $settings           = $this->get_settings_for_display();
        $per_page           = 1;

        $cart_action_classes = 'class="sp-cart-button '. $settings['product_action_button_class'].'"';
			

        add_filter( 'wc_add_to_cart_params', function( $params ) {
            // Don't modify params if we're on a WooCommerce page (delete if not needed).
            if ( is_woocommerce() ) {
            return $params;
            }
            
            // Set the 'View cart' text
            $params['i18n_view_cart'] = __( 'Go to cart',  'alpha-single-product-for-elementor' );
            // Set the 'View cart' URL
            $params['cart_url'] =  esc_url( wc_get_cart_url() ) ;
            return $params;
            } );

        // Query Argument
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $per_page,
        );

        $args['post__in'] = $settings['alphasp_product_id'];

        // Action Button
        $this->add_render_attribute( 'action_btn_attr', 'class', 'alpha-sp-action-btn-area' );

        if( isset($settings['addtocart_button_txt']) && $settings['addtocart_button_txt'] == 'yes' ){
            $this->add_render_attribute( 'action_btn_attr', 'class', 'alpha-sp-btn-text-cart' );
        }

        if ($settings['product_action_button_text']) {
            // To change add to cart text on single product page
            add_filter( 'woocommerce_product_single_add_to_cart_text',[$this,'add_to_cart_text'] ); 
            // To change add to cart text on product archives(Collection) page
            add_filter( 'woocommerce_product_add_to_cart_text', [$this,'add_to_cart_text'] );
        }
       
        ?>
        <div class="alpha-sp-product">
        <?php $products = new \WP_Query( $args );
        if( $products->have_posts() ):?>
            <?php while( $products->have_posts() ): 
                $products->the_post();
                // Gallery Image
                global $product;
                $gallery_images_ids = $product->get_gallery_image_ids() ? $product->get_gallery_image_ids() : array();
                if ( has_post_thumbnail() ){
                array_unshift( $gallery_images_ids, $product->get_image_id() );
                }?>
                    <!--Product Start-->
                        <div class="sp-product-wrapper">
                            <div class="sp-product-image">  
                                    <a href="<?php the_permalink();?>"> 
                                        <?php woocommerce_template_loop_product_thumbnail(); ?> 
                                    </a>
                            </div>
                            <div class="sp-product-action">
                                <div class="sp-product-info"> 
                                    <h4 class="sp-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <div class="sp-product-price"><?php woocommerce_template_loop_price();?></div>
                                </div>
                                <div <?php echo $cart_action_classes;?>><?php woocommerce_template_loop_add_to_cart(); ?></div>
                            </div>
                        </div>
                    <!--Product End-->
            <?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
        <?php endif;  ?>
        </div>
        <?php
    }
}
