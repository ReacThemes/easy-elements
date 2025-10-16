<?php
class Easy_Elements_Templates_Library {

	private $assets_url;
	private $rest_url;

	const SITE_URL  = 'https://reactheme.com/products/wordpress/invena/template/';

	protected static $instance = null;

	public function __construct() {

		// get current module's url.
		$this->assets_url = EASYELEMENTS_DIR_URL . 'elementor-template-library/assets/';

        // get current module's url.
		$this->rest_url = self::SITE_URL . '/wp-json/rtTemplates/v1/templates';
		
		// print variables on footer.
		add_action( 'elementor/editor/footer', array( $this, 'editor_footer_script' ) );

		// enqueue editor js for elementor.
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 1 );

		// enqueue editor css.
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

		// enqueue modal's preview css.
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );

	}

	 /**
     * Get instance
     */
    public static function getInstance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	public function editor_scripts() {
		wp_enqueue_script( 'easy-elements-template-library-script', $this->assets_url . 'js/easy-template-library.js', array( 'jquery', 'wp-element' ), EASYELEMENTS_VER, true );
		wp_enqueue_script( 'easy-elements-template-library-isotope-script', $this->assets_url . 'js/isotope.pkgd.min.js', array( 'jquery', 'wp-element' ), EASYELEMENTS_VER, true );
    }

	public function editor_styles() {
		wp_enqueue_style( 'easy-elements-template-library-style', $this->assets_url . 'css/easy-elements-template-library.css', array(), EASYELEMENTS_VER );
	}

	public function preview_styles() {
		wp_enqueue_style( 'easy-elements-template-library-preview-style', $this->assets_url . 'css/preview.css', array(), EASYELEMENTS_VER );
	}

	public function editor_footer_script() { ?>
		<script type="text/javascript">

            var rtElementsTemplatesManager = {
                "activeTab": "sections",
                "nonce": "<?php echo esc_attr(wp_create_nonce( 'wp_rest' )); ?>",
                "buttonIcon": "<?php echo  esc_url( $this->assets_url . 'img/rt-template-logo.webp' ); ?>",
                "logoUrl": "<?php echo  esc_url( $this->assets_url . 'img/rt-template-logo-sm.webp' ); ?>",
                "headerLogoUrl": "<?php echo  esc_url( $this->assets_url . 'img/rt-template-logo.webp' ); ?>",
                "bannerAdUrl": "<?php echo  esc_url( $this->assets_url . 'img/rttemplates-banner-ad.webp' ); ?>",
                "apiUrl": "<?php echo esc_url( $this->rest_url); ?>",
                "thumbnailPlaceholderUrl": "<?php echo  esc_url( $this->assets_url . 'img/about-img.jpg' ); ?>",
                "templatesContainer": document.querySelector('#rtElementsTemplatesLibrary #elementor-template-library-templates-container')
            };

		</script> 
		<?php
	}

}


Easy_Elements_Templates_Library::getInstance();