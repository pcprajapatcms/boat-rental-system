<?php
/**
 * The calendar-widget for showing the reservations of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class to manage the calendar widget settings and frontend display.
 */
class Easy_Reservations_Calendar_Widget extends WP_Widget {
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			ersrv_get_calendar_widget_base_id(), // Widget base ID.
			__( 'Easy Reservations: Calendar', 'easy-reservations' ), // Widget name will appear in UI.
			array(
				'description' => __( 'This widget offered by easy reservations plugin, shows the available/unavailable dates by reservable item.', 'easy-reservations' ), // Widget description.
			)
		);
	}

	/**
	 * Frontend template of the widget.
	 * Shows the reservable items and their availability dates.
	 *
	 * @param array $args Holds the widget arguments.
	 * @param array $instance Holds the widget settings data.
	 */
	public function widget( $args, $instance ) {
		$widget_title                = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$widget_desc                 = ( ! empty( $instance['description'] ) ) ? $instance['description'] : '';
		$display_reserve_item_button = ( ! empty( $instance['display_reserve_item_button'] ) ) ? 'yes' : 'no';
		$reservable_items_query      = ersrv_get_posts( 'product', 1, -1 );
		$reservable_items            = $reservable_items_query->posts;
		$reserve_item_button_text    = __( 'Reserve this item', 'easy-reservations' );
		$current_theme       		 = get_option( 'stylesheet' );
		/**
		 * This hook fires within the calendar widget.
		 *
		 * This hook helps in modifying the reserve item button text within the calendar widget.
		 *
		 * @param string $reserve_item_button_text Holds the button text.
		 * @return string
		 * @since 1.0.0
		 */
		$reserve_item_button_text = apply_filters( 'ersrv_reserve_item_button_text_widget', $reserve_item_button_text );

		// Display the calendar widget with reservable items.
		ob_start();
		?>
		<div class="ersrv-reservation-widget-container">
			<?php
			// Print the widget title.
			if ( ! empty( $widget_title ) ) {
				echo wp_kses_post( '<h2 class="widget-title">'. $widget_title .'</h2>' );
			}

			// Print the widget description.
			if ( ! empty( $widget_desc ) ) {
				echo wp_kses(
					'<h6 class="description">' . $widget_desc . '</h6>',
					array(
						'h6' => array(
							'class' => array(),
						),
					)
				);
			}

			if ( ! empty( $reservable_items ) && is_array( $reservable_items ) ) {
				?>
				<div class="selectbox">
					<select class="selectpicker form-control calender-select" id="ersrv-widget-reservable-items" data-size="5" data-style="btn-outline-light focus-none">
						<option value="-1"><?php esc_html_e( 'Select Item', 'easy-reservations' ); ?></option>
						<?php foreach ( $reservable_items as $reservable_item_id ) { ?>
							<option value="<?php echo esc_attr( $reservable_item_id ); ?>"><?php echo wp_kses_post( get_the_title( $reservable_item_id ) ); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="ersrv-widget-calendar"></div>

				<?php if ( ! empty( $display_reserve_item_button ) && 'yes' === $display_reserve_item_button ) {?>
					<div class="ersrv-book-item-from-widget text-center">
						<a title="<?php echo wp_kses_post( $reserve_item_button_text ); ?>" href="javascript:void(0);" class="<?php echo esc_attr( ( 'twentysixteen' === $current_theme || 'twentyseventeen' === $current_theme ) ? 'btn' : '' ); ?> button mt-2"><?php echo wp_kses_post( $reserve_item_button_text ); ?></a>
					</div>
				<?php } ?>
				<?php
			} else {
				echo wp_kses(
					'<p class="ersrv-no-reservable-items">' . __( 'There are no reservable items.', 'easy-reservations' ) . '</p>',
					array(
						'p' => array(
							'class' => array(),
						),
					)
				);
			}
			?>
		</div>
		<?php
		echo wp_kses(
			ob_get_clean(),
			array(
				'a'      => array(
					'title' => array(),
					'href'  => array(),
					'class' => array(),
				),
				'div'    => array(
					'class' => array(),
				),
				'select' => array(
					'id' => array(),
					'class' => array(),
				),
				'option' => array(
					'value' => array(),
				),
				'p'      => array(
					'class' => array(),
				),
				'h2'     => array(
					'class' => array(),
				),
				'h6'     => array(
					'class' => array(),
				),
			)
		);
	}

	/**
	 * Widget admin settings.
	 *
	 * @param array $instance Widget instance.
	 */
	public function form( $instance ) {
		$title                       = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'New title', 'easy-reservations' );
		$description                 = ( ! empty( $instance['description'] ) ) ? $instance['description'] : '';
		$display_reserve_item_button = ( ! empty( $instance['display_reserve_item_button'] ) ) ? 'yes' : 'no';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'easy-reservations' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo wp_kses_post( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'easy-reservations' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo wp_kses_post( $description ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_reserve_item_button' ) ); ?>"><?php esc_html_e( 'Display Reserve Item Button:', 'easy-reservations' ); ?></label>
			<input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'display_reserve_item_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_reserve_item_button' ) ); ?>" <?php echo esc_attr( ( ! empty( $display_reserve_item_button ) && 'yes' === $display_reserve_item_button ) ? 'checked' : '' ); ?> />
		</p>
		<?php
	}

	/**
	 * Updating widget replacing old instances with new.
	 *
	 * @param array $new_instance New instance.
	 * @param array $old_instance Old instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                                = array();
		$instance['title']                       = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['description']                 = ( ! empty( $new_instance['description'] ) ) ? $new_instance['description'] : '';
		$instance['available_dates_bg_color']    = ( ! empty( $new_instance['available_dates_bg_color'] ) ) ? $new_instance['available_dates_bg_color'] : '';
		$instance['display_reserve_item_button'] = ( ! empty( $new_instance['display_reserve_item_button'] ) ) ? $new_instance['display_reserve_item_button'] : '';

		return $instance;
	}
}
