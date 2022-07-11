<?php
namespace Elementor;

class parent_product_by_cat extends Widget_Base {

    public function get_name() {
		return 'parent_product_by_cat';
	}

	public function get_title() {
		return __( 'Parent Product By Category' );
	}

	public function get_icon() {
		return 'eicon-post-list';
    }


  //  public function __construct($data = [], $args = null)
  // {
  //   parent::__construct($data, $args);
  //   wp_enqueue_style( 'parent-product-by-cat', plugin_dir_url( __DIR__  ) . '../css/rsb/parent-product-by-cat.css','1.1.0');
  // }

   public function get_style_depends() {
      wp_register_style( 'parent-product-by-cat', plugin_dir_url( __DIR__  ) . '../css/rsb/parent-product-by-cat.css','1.1.0');
     return [ 'parent-product-by-cat' ];
   }




	protected function _register_controls() {
		$mine = array();
    $categories = get_terms(array(
            'taxonomy' => 'product_cat', 
			'orderby'   => 'name',
			'order'     => 'ASC'
		));

		foreach ($categories as $category ) {
	     $mine[$category->term_id] = $category->name;
		}

			$this->start_controls_section(
				'content_section',
				[
					'label' => __( 'Content', 'post-plus' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

      $this->add_control(
        'title',
        [
          'type' => \Elementor\Controls_Manager::TEXT,
          'label' => esc_html__( 'ชื่อหัวข้อ ไทย', 'yp-core' ),
          'placeholder' => esc_html__( 'กรอกชื่อหัวข้อ', 'yp-core' ),
        ]
      );

      $this->add_control(
        'title_eng',
        [
          'type' => \Elementor\Controls_Manager::TEXT,
          'label' => esc_html__( 'ชื่อหัวข้อ อังกฤษ', 'yp-core' ),
          'placeholder' => esc_html__( 'Enter your title', 'yp-core' ),
        ]
      );
  
        // Post categories.
		$this->add_control(
			'category',
			[
        'label' => '<i class="fa fa-folder"></i> ' . __( 'Category', 'yp-core' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => 'none',
        'options'   => $mine,
				'multiple' => false,
			]
		);

    $this->add_control(
			'image',
			[
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label' => esc_html__( 'เลือกภาพพื้นหลัง', 'plugin-name' ),
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				]
			]
		);

    $this->add_control(
        'per_posts',
        [
          'label' => __( 'Posts Per Page', 'yp-core' ),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'placeholder' => '0',
          'min' => 1,
          'max' => 12,
          'step' => 1,
          'default' => 1,
        ]
      );

      $this->add_control(
          'post_offset',
          [
            'label' => __( 'Offset', 'yp-core' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'placeholder' => '0',
            'min' => 1,
            'max' => 12,
            'step' => 1,
            'default' => 0,
          ]
        );
      
        $this->add_control(
          'column',
          [
            'type' => \Elementor\Controls_Manager::SELECT,
            'label' => esc_html__( 'Column', 'plugin-name' ),
            'options' => [
              '1' => esc_html__( '1', 'yp-core' ),
              '2' => esc_html__( '2', 'yp-core' ),
              '3' => esc_html__( '3', 'yp-core' ),
              '4' => esc_html__( '4', 'yp-core' ),
              '5' => esc_html__( '5', 'yp-core' ),
            ],
            'default' => '1',
          ]
        );

        $this->end_controls_section();
		}

	protected function render() {
    $settings = $this->get_settings_for_display();
    $offset = $settings['post_offset'];
    if ($offet == '') {
      $offet = 0;
    }
    $num_posts = $settings['per_posts'];
    if ($num_posts == '') {
        $num_posts = 1;
    }
    $cat_x = $settings['category'];
    if ($cat_x == '') {
        $cat_x = 1;
    }
    $column   = $settings['column'];
    switch ($column ) {
      case 1:
        $num_column = 1;
        $num_column_tablet = 1;
        $num_column_mobile = 1;
        $c_class = 'c_1_class';
      break;
      case 2:
        $num_column = 2;
        $num_column_tablet = 2;
        $num_column_mobile = 1;
        $c_class = 'c_2_class';
      break;
      case 3:
        $num_column = 3;
        $num_column_tablet = 3;
        $num_column_mobile = 2;
        $c_class = 'c_3_class';
      break;  
      case 4:
        $num_column = 4;
        $num_column_tablet = 4;
        $num_column_mobile = 2;
        $c_class = 'c_4_class';
      break;     
      case 5:
        $num_column = 5;
        $num_column_tablet = 4;
        $num_column_mobile = 2;
        $c_class = 'c_5_class';
      break; 
      default:
        $num_column = 4;
        $num_column_tablet = 4;
        $num_column_mobile = 2;
        $c_class = 'c_4_class';
        break;
    }

    $term_all = get_term_by('id', $settings['category'], 'product_cat');
    $term_link = get_term_link( $term_all->term_id , 'product_cat' );
    $background_image = get_field('background_image', 'term_' . $term_all->term_id);
    $bg_url =  wp_get_attachment_image_url( $settings['image']['id'], 'large' );
    ?>
    <div class="grid-product_cat v1">
        <div class="post-grid_bar_product" 
        style="background-image: url(<?php 
        if(!empty( $bg_url ) ){ echo $bg_url; }else{ echo plugin_dir_url( __DIR__  ) . '../image/thumb.png'; } 
        ?>);">
          <div class="grid-title">
            <?php if($settings['title']): ?>
              <h3><?php echo $settings['title']; ?>
                <?php if($settings['title_eng']):?>
                <span>
                  <?php echo $settings['title_eng']; ?>
                </span>
                <?php endif;?>
             </h3>
              <h5><span><?php echo esc_html__( 'by RSB Furniture', 'yp-core' ); ?></span></h5>
            <?php else:?>
              <h3><?php echo $term_all->name; ?></h3>
              <h5><span><?php echo esc_html__( 'by RSB Furniture', 'yp-core' ); ?></span></h3>
            <?php endif;?>
          </div>
        </div>
        <div class="grid-product_cat_list v1">
        <div class="product-wrapper">
        <?php
                            $args = array (
                                'taxonomy' => 'product_cat', //empty string(''), false, 0 don't work, and return empty array
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'number' => $settings['per_posts'],
                                'hide_empty' => false, //can be 1, '1' too
                                'hierarchical' => true, //can be 1, '1' too
                                'child_of' => $settings['category'], //can be 0, '0', '' too
                            );
                            $terms = get_terms('product_cat', $args);


                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                                ?>
                            <div class="taxonomy-list_box">
                                <ul class="list-cat_ul <?php echo $c_class; ?>" style="grid-template-columns: repeat(<?php echo $num_column; ?>, 1fr);">
                                <?php                              
                                foreach($terms as $term){
                                  $term_link = get_term_link( $term->term_id );
                                  $image_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
                                  $post_thumbnail_img = wp_get_attachment_image_src( $image_id, 'category-thumb' );
                                    ?>
                                    <li class="list-cat">
                                        <div class="box-image_cat">
                                          <div class="img-box_cat">
                                              <a href="<?php echo $term_link; ?>">
                                              <?php if($post_thumbnail_img): ?>
                                                <img src="<?php echo $post_thumbnail_img[0]; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" 
                                                alt="<?php echo $term->name; ?>">
                                                <?php else: ?>
                                                <img src="<?php echo plugin_dir_url( __DIR__  ) . '../image/thumb.png'; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail img-empty" 
                                                alt="rsb">
                                                <?php endif; ?>
                                              </a>
                                            </div>
                                        </div>
                                        <div class="title-link">
                                            <a href="<?php echo $term_link; ?>"><?php echo $term->name; ?></a>
                                        </div>                                       
                                    </li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </div>
                                <?php
                            }
                            ?>
      </div>
    </div>
    </div>

    <style>
/*ipad (tablet)*/
@media (max-width: 1024px) {
  .grid-product_cat ul.list-cat_ul {
    grid-template-columns: repeat(<?php echo $num_column_tablet; ?>, 1fr) !important;
    grid-gap: 10px;
}
  
}
/*iphone8 (smartphone)*/
@media (max-width: 575.98px) {
  .grid-product_cat ul.list-cat_ul {
    grid-template-columns: repeat(<?php echo $num_column_mobile; ?>, 1fr) !important;
}
}
 </style>
		<?php
    }

	protected function _content_template() {}


}
