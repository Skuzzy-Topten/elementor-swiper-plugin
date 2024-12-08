<?php
class Elementor_Swiper_Widget extends \Elementor\Widget_Base {

    // Widget Data
	public function get_name(): string {
		return 'Swiper Post Carousel';
	}

	public function get_title(): string {
		return esc_html__( 'Swiper Post Carousel', 'textdomain' );
	}

	public function get_icon(): string {
		return 'eicon-carousel';
	}

	public function get_categories(): array {
		return [ 'basic' ];
	}

	public function get_categories_options() {
        $categories = get_categories([
            'orderby' => 'name',
            'hide_empty' => false,
        ]);

        $options = [];
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }

        return $options;
    }

	public function get_keywords(): array {
		return [ 'swiper', 'carousel', 'post', 'post carousel', 'swiper post', 'swiper post carousel' ];
	}

	public function get_script_depends(): array{
        return [ 'swiper-post-carousel-widget-script-1' ];
	}

	public function get_style_depends(): array{
	   return [ 'swiper-post-carousel-widget-style-1' ];
	}

	// Widget Rendering
	protected function render(): void {
	    $settings = $this->get_settings_for_display();
		$swiper_rand_name = "new_swiper".rand(100000,999999);
		$swiper_name = empty($settings['swiper_name']) ? $swiper_rand_name : $settings['swiper_name'];
		$swiper_order = empty($settings['swiper_order_by']) ? 'ASC' : $settings['swiper_order_by'];
	    ?>
			<div class="swiper <?php echo $swiper_name; ?>">
                <div class="swiper-wrapper">
                    <?php
                        $arguments = array(
                            "posts_per_page" => $settings['swiper_post_per_page'],
                            "category" => $settings['swiper_post_category'],
                            "orderby" => "date",
                            "order" => $swiper_order,
                        );
                        $posts = get_posts($arguments);
                        foreach($posts as $post)
                        {
                            $post_link = get_permalink($post);
                            $post_featured_image = get_the_post_thumbnail_url($post, 'full');
                            ?>
                                <div class="swiper-slide">
                                    <?php
                                        if ( 'show' === $settings['swiper_post_featured_image'] ) {
                                            if ( 'on' === $settings['swiper_post_featured_image_link'] ) {
                                    ?>
                                        <a href="<?php echo $post_link; ?>">
                                        <?php
                                            }
                                        ?>
                                            <img class="swiper-card-featured-image" src="<?php echo $post_featured_image; ?>" alt="<?php echo $post->post_title; ?>">
                                        <?php
                                            if ( 'on' === $settings['swiper_post_featured_image_link'] ) {
                                        ?>
                                            </a>
                                        <?php
                                            }
                                        }
                                    ?>
                                    <?php
                                        if ( 'show' === $settings['swiper_post_title'] ) {
                                            if ( 'on' === $settings['swiper_post_title_link'] ) {
                                    ?>
                                            <a href="<?php echo $post_link; ?>">
                                        <?php
                                            }
                                        ?>
                                            <h4 class="swiper-card-title">
                                                <?php echo $post->post_title; ?>
                                            </h4>
                                        <?php
                                            if ( 'on' === $settings['swiper_post_title_link'] ) {
                                        ?>
                                            </a>
                                    <?php
                                            }
                                        }
                                    ?>
                                    <?php
                                        if ( 'show' === $settings['swiper_post_excerpt'] ) {
                                            if ( 'on' === $settings['swiper_post_excerpt_link'] ) {
                                    ?>
                                            <a href="<?php echo $post_link; ?>">
                                        <?php
                                            }
                                        ?>
                                            <p class="swiper-card-excerpt">
                                                <?php echo $post->post_excerpt; ?>
                                            </p>
                                        <?php
                                            if ( 'on' === $settings['swiper_post_excerpt_link'] ) {
                                        ?>
                                            </a>
                                    <?php
                                            }
                                        }
                                    ?>
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-scrollbar-spacer"></div>
                <div class="swiper-scrollbar"></div>
            </div>
            <script>
                var swiper = new Swiper(".<?php echo $swiper_name; ?>", {
                    spaceBetween: <?php if($settings['slide_space_between'] == NULL){ echo "0"; }else{ echo $settings['slide_space_between'];} ?>,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: <?php if($settings['swiper_pagination_clickable'] == NULL){ echo "false"; }else{ echo $settings['swiper_pagination_clickable']; } ?>,
                    },
                    scrollbar: {
                        el: ".swiper-scrollbar",
                        draggable: <?php if($settings['swiper_scrollbar_draggable'] == NULL){ echo "false"; }else{ echo $settings['swiper_scrollbar_draggable']; } ?>,
                        hide: <?php if($settings['swiper_scrollbar'] == NULL){ echo "true"; }else{ echo $settings['swiper_scrollbar']; } ?>,
                    },
                    breakpoints: {
                        <?php
                            foreach($settings['swiper_breakpoints'] as $swiper_breakpoint) {
                            $swiper_responsive_ratio = intval($swiper_breakpoint['swiper_responsive_ratio']);
                            $swiper_slide_per_view = intval($swiper_breakpoint['swiper_slide_per_view']);
                        ?>
                            <?php echo $swiper_responsive_ratio; ?>: {
                                slidesPerView: <?php echo $swiper_slide_per_view; ?>,
                            },
                        <?php
                            }
                        ?>
                    },
                });
            </script>
		<?php
	}

	protected function content_template(): void {
	    ?>
		<?php
	}

	// Widget Controls
	protected function register_controls(): void {

	    // Content - Swiper Breakpoints Section
	    $this->start_controls_section(
			'swiper_breakpoints_content_section',
			[
				'label' => esc_html__( 'Swiper Breakpoints', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		    // Swiper Breakpoints
            $repeater = new \Elementor\Repeater();
            $repeater->add_control(
    			'swiper_responsive_ratio',
    			[
    				'label' => esc_html__( 'Responsive Ratio', 'elementor-list-widget' ),
    				'type' => \Elementor\Controls_Manager::TEXT,
    				'label_block' => true,
    			]
    		);
            $repeater->add_control(
     			'swiper_slide_per_view',
     			[
    				'label' => esc_html__( 'Slide Per View', 'elementor-list-widget' ),
    				'type' => \Elementor\Controls_Manager::NUMBER,
     			]
      		);
    		$this->add_control(
    			'swiper_breakpoints',
    			[
    				'label' => esc_html__( 'Breakpoints', 'elementor-list-widget' ),
    				'type' => \Elementor\Controls_Manager::REPEATER,
    				'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ swiper_responsive_ratio }}}px - {{{ swiper_slide_per_view }}} slides',
    			]
    		);

			$this->add_control(
     			'swiper_breakpoints_content_hr',
     			[
                    'type' => \Elementor\Controls_Manager::DIVIDER,
     			]
      		);

            // Slide Space Between
    		$this->add_control(
    			'slide_space_between',
    			[
    				'label' => esc_html__( 'Slide Space Between', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::NUMBER,
    				'min' => 0,
    				'step' => 0,
    				'default' => 10,
    			]
    		);

		$this->end_controls_section();

		// Content - Swiper Name Section
		$this->start_controls_section(
            'swiper_name_section',
            [
               	'label' => esc_html__( 'Swiper Name', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Swiper Name
			$this->add_control(
				'swiper_name',
				[
					'label' => esc_html__( 'Swiper Name', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'new_swiper_'.rand(100000,999999), 'textdomain' ),
					'placeholder' => esc_html__( 'Type your title here', 'textdomain' ),
				]
			);

        $this->end_controls_section();

		// Content - Swiper Post Category Section
		$this->start_controls_section(
            'swiper_post_category_section',
            [
               	'label' => esc_html__( 'Swiper Post Category', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Post Category
			$this->add_control(
                'swiper_post_category',
                [
                    'label' => __( 'Post Category', 'your-plugin' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => $this->get_categories_options(),
                    'multiple' => true,
                    'label_block' => true,
                ]
            );

        $this->end_controls_section();

        // Content - Swiper Post Per Page Section
		$this->start_controls_section(
            'swiper_post_per_page_section',
            [
               	'label' => esc_html__( 'Swiper Post Per Page', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Post Per Page
    		$this->add_control(
    			'swiper_post_per_page',
    			[
    				'label' => esc_html__( 'Post Per Page', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::NUMBER,
    				'min' => 0,
    				'step' => 0,
    				'default' => '',
    			]
    		);

        $this->end_controls_section();

		// Content - Swiper Featured Image Section
		$this->start_controls_section(
            'swiper_post_featured_image_section',
            [
               	'label' => esc_html__( 'Swiper Post Featured Image', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Post Featured Image
            $this->add_control(
          		'swiper_post_featured_image',
          		[
              		'label' => esc_html__( 'Post Featured Image', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'show',
              		'default' => 'show',
          		]
      		);
            // Post Featured Image Link
            $this->add_control(
          		'swiper_post_featured_image_link',
          		[
              		'label' => esc_html__( 'Post Featured Image Link', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'Yes', 'textdomain' ),
              		'label_off' => esc_html__( 'No', 'textdomain' ),
              		'return_value' => 'on',
              		'default' => 'off',
          		]
      		);

        $this->end_controls_section();

        // Content - Swiper Post Title Section
		$this->start_controls_section(
            'swiper_post_title_section',
            [
               	'label' => esc_html__( 'Swiper Post Title', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Post Title
            $this->add_control(
          		'swiper_post_title',
          		[
              		'label' => esc_html__( 'Post Title', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'show',
              		'default' => 'show',
          		]
      		);
            // Post Title Link
            $this->add_control(
          		'swiper_post_title_link',
          		[
              		'label' => esc_html__( 'Post Title Link', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'Yes', 'textdomain' ),
              		'label_off' => esc_html__( 'No', 'textdomain' ),
              		'return_value' => 'on',
              		'default' => 'off',
          		]
      		);

        $this->end_controls_section();

        // Content - Swiper Post Excerpt Section
		$this->start_controls_section(
            'swiper_post_excerpt_section',
            [
               	'label' => esc_html__( 'Swiper Post Excerpt', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Post Excerpt
            $this->add_control(
          		'swiper_post_excerpt',
          		[
              		'label' => esc_html__( 'Post Excerpt', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'show',
              		'default' => 'show',
          		]
      		);
            // Post Excerpt Link
            $this->add_control(
          		'swiper_post_excerpt_link',
          		[
              		'label' => esc_html__( 'Post Excerpt Link', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'Yes', 'textdomain' ),
              		'label_off' => esc_html__( 'No', 'textdomain' ),
              		'return_value' => 'on',
              		'default' => 'off',
          		]
      		);

        $this->end_controls_section();

        // Content - Swiper Post Pagination Section
		$this->start_controls_section(
            'swiper_post_pagination_section',
            [
               	'label' => esc_html__( 'Swiper Post Pagination', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Swiper Pagination
            $this->add_control(
          		'swiper_pagination',
          		[
              		'label' => esc_html__( 'Swiper Pagination', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'show',
              		'default' => 'show',
          		]
            );
            // Swiper Pagination Clickable
            $this->add_control(
          		'swiper_pagination_clickable',
          		[
              		'label' => esc_html__( 'Swiper Pagination Clickable', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'Yes', 'textdomain' ),
              		'label_off' => esc_html__( 'No', 'textdomain' ),
              		'return_value' => 'true',
              		'default' => 'true',
          		]
            );

        $this->end_controls_section();

        // Content - Swiper Post Scrollbar Section
		$this->start_controls_section(
            'swiper_post_scrollbar_section',
            [
               	'label' => esc_html__( 'Swiper Post Scrollbar', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Swiper Scrollbar
            $this->add_control(
          		'swiper_scrollbar',
          		[
              		'label' => esc_html__( 'Swiper Scrollbar', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'false',
              		'default' => 'false',
          		]
            );
            // Swiper Scrollbar Draggable
            $this->add_control(
          		'swiper_scrollbar_draggable',
          		[
              		'label' => esc_html__( 'Swiper Scrollbar Draggable', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'Yes', 'textdomain' ),
              		'label_off' => esc_html__( 'No', 'textdomain' ),
              		'return_value' => 'true',
              		'default' => 'true',
          		]
            );

        $this->end_controls_section();

        // Content - Swiper Overflow Section
		$this->start_controls_section(
            'swiper_overflow_section',
            [
               	'label' => esc_html__( 'Swiper Overflow', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Swiper Overflow
            $this->add_control(
          		'swiper_overflow',
          		[
              		'label' => esc_html__( 'Swiper Overflow', 'textdomain' ),
              		'type' => \Elementor\Controls_Manager::SWITCHER,
              		'label_on' => esc_html__( 'On', 'textdomain' ),
              		'label_off' => esc_html__( 'Off', 'textdomain' ),
              		'return_value' => 'visible',
              		'default' => 'hidden',
                    'selectors' => [
    				'{{WRAPPER}} .swiper' => 'overflow: {{return_value}} !important;',
                    'body' => 'overflow-x: hidden !important;',
    				],
          		]
      		);

        $this->end_controls_section();

        // Content - Swiper Order By Section
		$this->start_controls_section(
            'swiper_order_by_section',
            [
               	'label' => esc_html__( 'Swiper Order By', 'textdomain' ),
               	'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            // Swiper Order By
            $this->add_control(
    			'swiper_order_by',
    			[
    				'label' => esc_html__( 'Swiper Order By', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::SWITCHER,
    				'label_on' => esc_html__( 'DESC', 'textdomain' ),
    				'label_off' => esc_html__( 'ASC', 'textdomain' ),
    				'return_value' => 'DESC',
    				'default' => 'DESC',
    			]
    		);

        $this->end_controls_section();

		// Style - Swiper Slide Section
		$this->start_controls_section(
			'swiper_slide_style_section',
			[
				'label' => esc_html__( 'Swiper Slide', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->start_controls_tabs(
				'style_tabs'
			);

			    // Normal
                $this->start_controls_tab(
					'style_normal_tab',
					[
						'label' => esc_html__( 'Normal', 'textdomain' ),
					]
				);

				    // Swiper Slide Transform
					$this->add_control(
						'swiper_slide_transform',
						[
							'label' => esc_html__( 'Swiper Slide Transform (Offset Y)', 'textdomain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'step' => 0,
								],
							],
							'default' => [
								'unit' => 'px',
							],
							'selectors' => [
								'{{WRAPPER}} .swiper-slide' => 'transform: translateY({{SIZE}}{{UNIT}});',
							],
						]
					);
					// Swiper Slide Transition
					$this->add_control(
						'swiper_slide_transition',
						[
							'label' => esc_html__( 'Swiper Slide Transition', 'textdomain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 's' ],
							'range' => [
								'px' => [
									'step' => 0,
								],
							],
							'default' => [
								'unit' => 's',
							],
							'selectors' => [
								'{{WRAPPER}} .swiper-slide' => 'transition: transform {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();

				// Hover
				$this->start_controls_tab(
					'style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'textdomain' ),
					]
				);

				    // Swiper Slide Translate Hover
					$this->add_control(
						'swiper_slide_transform_hover',
						[
							'label' => esc_html__( 'Swiper Slide Transform (Offset Y)', 'textdomain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'step' => 0,
								],
							],
							'default' => [
								'unit' => 'px',
							],
							'selectors' => [
							    '{{WRAPPER}} .swiper-slide:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
							],
						]
					);
					// Swiper Slide Transition Hover
					$this->add_control(
						'swiper_slide_transition_hover',
						[
							'label' => esc_html__( 'Swiper Slide Transition', 'textdomain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 's' ],
							'range' => [
								'px' => [
									'step' => 0,
								],
							],
							'default' => [
								'unit' => 's',
							],
							'selectors' => [
								'{{WRAPPER}} .swiper-slide:hover' => 'transition: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		// Style - Swiper Post Featured Image Section
		$this->start_controls_section(
			'swiper_slide_post_featured_image_section',
			[
				'label' => esc_html__( 'Swiper Post Featured Image', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            // Post Featured Image Border Radius
            $this->add_responsive_control(
                'post_featured_image_border_radius',
     			[
    				'label' => esc_html__( 'Post Feature Image Border Radius', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px' ],
    				'default' => [
   					'unit' => 'px',
   					'isLinked' => true,
    				],
    				'selectors' => [
   					'{{WRAPPER}} .swiper-card-featured-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
     			]
            );
            // Post Featured Image Padding
            $this->add_responsive_control(
                'post_featured_image_padding',
     			[
    				'label' => esc_html__( 'Post Featured Image Padding', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px' ],
    				'default' => [
   					'unit' => 'px',
   					'isLinked' => true,
    				],
    				'selectors' => [
   					'{{WRAPPER}} .swiper-card-featured-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
     			]
            );
            // Post Featured Image Margin
            $this->add_responsive_control(
                'post_featured_image_margin',
     			[
    				'label' => esc_html__( 'Post Featured Image Margin', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px' ],
    				'default' => [
   					'unit' => 'px',
   					'isLinked' => true,
    				],
    				'selectors' => [
   					'{{WRAPPER}} .swiper-card-featured-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
     			]
            );

		$this->end_controls_section();

		// Style - Swiper Post Title Section
		$this->start_controls_section(
			'swiper_slide_post_title_section',
			[
				'label' => esc_html__( 'Swiper Post Title', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->start_controls_tabs(
                'swiper_post_title_style_tabs'
                );

                // Normal
                $this->start_controls_tab(
                    'swiper_post_title_style_normal_tab',
                    [
                    'label' => esc_html__( 'Normal', 'textdomain' ),
                    ]
                );

                    // Post Title
    				$this->add_group_control(
       					\Elementor\Group_Control_Typography::get_type(),
       					[
      						'name' => 'post_title_typography',
      						'selector' => '{{WRAPPER}} .swiper-card-title',
      						'label' => esc_html__( 'Post Title', 'textdomain' ),
      						'label_block' => true,
       					]
    				);
                    // Post Title Color
                    $this->add_control(
             			'swiper_post_title_color',
             			[
            				'label' => esc_html__( 'Post Title Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-title' => 'color: {{VALUE}}',
            				],
             			]
              		);
                    // Post Title Padding
                    $this->add_responsive_control(
                        'swiper_post_title_padding',
             			[
            				'label' => esc_html__( 'Post Title Padding', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::DIMENSIONS,
            				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            				'default' => [
           					'unit' => 'px',
           					'isLinked' => true,
            				],
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            				],
             			]
                    );
                    // Post Title Margin
                    $this->add_responsive_control(
                        'swiper_post_title_margin',
             			[
            				'label' => esc_html__( 'Post Title Margin', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::DIMENSIONS,
            				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            				'default' => [
           					'unit' => 'px',
           					'isLinked' => true,
            				],
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            				],
             			]
          		    );

                $this->end_controls_tab();

                // Hover
				$this->start_controls_tab(
					'swiper_post_title_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'textdomain' ),
					]
				);

                    // Post Title Hover Color
                   	$this->add_control(
             			'swiper_post_title_hover_color',
             			[
            				'label' => esc_html__( 'Post Title Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-title:hover' => 'color: {{VALUE}}',
            				],
             			]
              		);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();


		// Style - Swiper Post Excerpt Section
		$this->start_controls_section(
			'swiper_slide_post_excerpt_section',
			[
				'label' => esc_html__( 'Swiper Post Excerpt', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->start_controls_tabs(
                'swiper_post_excerpt_style_tabs'
                );

                // Normal
                $this->start_controls_tab(
                    'swiper_post_excerpt_style_normal_tab',
                    [
                    'label' => esc_html__( 'Normal', 'textdomain' ),
                    ]
                );

                    // Post Excerpt
    				$this->add_group_control(
       					\Elementor\Group_Control_Typography::get_type(),
       					[
      						'name' => 'post_excerpt_typography',
      						'selector' => '{{WRAPPER}} .swiper-card-excerpt',
      						'label' => esc_html__( 'Post Excerpt', 'textdomain' ),
      						'label_block' => true,
       					]
    				);
                    // Post Excerpt Color
                    $this->add_control(
             			'swiper_post_excerpt_color',
             			[
            				'label' => esc_html__( 'Post Excerpt Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-excerpt' => 'color: {{VALUE}}',
            				],
             			]
              		);
                    // Post Excerpt Padding
                    $this->add_responsive_control(
                        'swiper_post_excerpt_padding',
             			[
            				'label' => esc_html__( 'Post Excerpt Padding', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::DIMENSIONS,
            				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            				'default' => [
           					'unit' => 'px',
           					'isLinked' => true,
            				],
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            				],
             			]
                    );
                    // Post Excerpt Margin
                    $this->add_responsive_control(
                        'swiper_post_excerpt_margin',
             			[
            				'label' => esc_html__( 'Post Excerpt Margin', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::DIMENSIONS,
            				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            				'default' => [
           					'unit' => 'px',
           					'isLinked' => true,
            				],
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            				],
             			]
          		    );

                $this->end_controls_tab();

                // Hover
				$this->start_controls_tab(
					'swiper_post_excerpt_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'textdomain' ),
					]
				);

                    // Post Excerpt Hover Color
                    $this->add_control(
             			'swiper_post_excerpt_hover_color',
             			[
            				'label' => esc_html__( 'Post Excerpt Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-card-excerpt:hover' => 'color: {{VALUE}}',
            				],
             			]
              		);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		// Style - Swiper Pagination Section
		$this->start_controls_section(
			'swiper_pagination_section',
			[
				'label' => esc_html__( 'Swiper Pagination', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		    // Swiper Padding Bullet Color
            $this->add_control(
     			'swiper_pagination_bullet_color',
     			[
    				'label' => esc_html__( 'Swiper Pagination Bullet Color', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::COLOR,
    				'selectors' => [
   					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}}; opacity: 1 !important;',
    				],
     			]
      		);
            // Swiper Padding Bullet Active Color
            $this->add_control(
     			'swiper_pagination_bullet_active_color',
     			[
    				'label' => esc_html__( 'Swiper Pagination Bullet Active Color', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::COLOR,
    				'selectors' => [
   					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
    				],
     			]
      		);
            // Swiper Pagination Padding
            $this->add_responsive_control(
                'swiper_pagination_padding',
     			[
    				'label' => esc_html__( 'Swiper Pagination Padding', 'textdomain' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px' ],
    				'default' => [
   					'unit' => 'px',
   					'isLinked' => true,
    				],
    				'selectors' => [
   					'{{WRAPPER}} .swiper-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
     			]
            );

		$this->end_controls_section();

		// Style - Swiper Scrollbar Section
		$this->start_controls_section(
			'swiper_scrollbar_section',
			[
				'label' => esc_html__( 'Swiper Scrollbar', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

            $this->start_controls_tabs(
                'swiper_scrollbar_style_tabs'
                );

                // Normal
                $this->start_controls_tab(
                    'swiper_scrollbar_style_normal_tab',
                    [
                    'label' => esc_html__( 'Normal', 'textdomain' ),
                    ]
                );

                    // Swiper Scrollbar Spacer
                    $this->add_control(
             			'swiper_scrollbar_spacer',
             			[
            				'label' => esc_html__( 'Swiper Scrollbar Spacer (px)', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::SLIDER,
            				'size_units' => [ 'px' ],
            				'range' => [
               					'px' => [
              						'min' => 0,
              						'step' => 0,
               					],
            				],
            				'selectors' => [
           					'{{WRAPPER}} .swiper-scrollbar-spacer' => 'padding: {{SIZE}}{{UNIT}};',
            				],
             			]
              		);

                    // Swiper Scrollbar Color
                    $this->add_control(
             			'swiper_scrollbar_color',
             			[
            				'label' => esc_html__( 'Swiper Scrollbar Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-scrollbar.swiper-scrollbar-horizontal' => 'background-color: {{VALUE}}',
            				],
             			]
              		);

                    // Swiper Scrollbar Drag Color
                    $this->add_control(
             			'swiper_scrollbar_drag_color',
             			[
            				'label' => esc_html__( 'Swiper Scrollbar Drag Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-scrollbar-drag' => 'background-color: {{VALUE}}',
            				],
             			]
              		);

                $this->end_controls_tab();

                // Hover
				$this->start_controls_tab(
					'swiper_scrollbar_style_hover_tab',
					[
						'label' => esc_html__( 'Hover', 'textdomain' ),
					]
				);

				    // Swiper Scrollbar Hover Color
                    $this->add_control(
             			'swiper_scrollbar_hover_color',
             			[
            				'label' => esc_html__( 'Swiper Scrollbar Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-scrollbar.swiper-scrollbar-horizontal:hover' => 'background-color: {{VALUE}}',
            				],
             			]
              		);

                    // Swiper Scrollbar Drag Hover Color
                    $this->add_control(
             			'swiper_scrollbar_drag_hover_color',
             			[
            				'label' => esc_html__( 'Swiper Scrollbar Drag Color', 'textdomain' ),
            				'type' => \Elementor\Controls_Manager::COLOR,
            				'selectors' => [
           					'{{WRAPPER}} .swiper-scrollbar-drag:hover' => 'background-color: {{VALUE}}',
            				],
             			]
              		);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

}
