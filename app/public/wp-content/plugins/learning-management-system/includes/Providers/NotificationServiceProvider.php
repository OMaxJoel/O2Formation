<?php
/**
 * Notification service provider.
 *
 * @since 1.4.1
 */

namespace Masteriyo\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Models\Notification;
use Masteriyo\Repository\NotificationRepository;
use Masteriyo\RestApi\Controllers\Version1\NotificationsController;
use Masteriyo\Query\UserCourseQuery;


class NotificationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.4.1
	 *
	 * @var array
	 */
	protected $provides = array(
		'notification',
		'notification.store',
		'notification.rest',
	);

	/**
	 * This method is called after all service providers are registered.
	 *
	 * @since 1.7.1
	 */
	public function boot() {
		add_action( 'masteriyo_new_user_course', array( $this, 'schedule_course_start_notification_to_student' ), 10, 2 );
		add_action( 'masteriyo_course_progress_status_changed', array( $this, 'schedule_course_end_notification_to_student' ), 10, 4 );
		add_action( 'masteriyo_order_status_completed', array( $this, 'schedule_completed_order_notification_to_student' ), 10, 2 );
		add_action( 'masteriyo_order_status_on-hold', array( $this, 'schedule_on_hold_order_notification_to_student' ), 10, 2 );
		add_action( 'masteriyo_order_status_cancelled', array( $this, 'schedule_cancelled_order_notification_to_student' ), 10, 2 );
		add_action( 'masteriyo_checkout_order_created', array( $this, 'schedule_order_created_notification_to_student' ), 10, 1 );
	}


	/**
	 * Schedule course start notification to student.
	 *
	 * @since 1.7.1
	 *
	 * @param int $id User course id.
	 * @param \Masteriyo\Models\UserCourse $object The user course object.
	 * @param \Masteriyo\Models\Order $order
	 */
	public function schedule_course_start_notification_to_student( $id, $user_course ) {
		$result = masteriyo_get_setting( 'notification.student.course_enroll' );
		if ( ! isset( $result ) ) {
			return;
		}
		if ( ! masteriyo_is_current_user_student( $user_course->get_id() ) ) {
			return;
		}

		$order_id = $user_course->get_order_id();
		if ( 0 !== $order_id ) {
			return;
		}

		masteriyo_set_notification( $id, $user_course, $result );
	}


	/**
	 * Schedule course end notifications to student.
	 *
	 * @since 1.7.1
	 *
	 * @param integer $id Course progress ID.
	 * @param string $old_status Old status.
	 * @param string $new_status New status.
	 * @param \Masteriyo\Models\CourseProgress $course_progress The course progress object.
	 */
	public function schedule_course_end_notification_to_student( $id, $old_status, $new_status, $user_course ) {
		$result = masteriyo_get_setting( 'notification.student.course_complete' );
		if ( ! isset( $result ) ) {
			return;
		}
		if ( ! masteriyo_is_current_user_student( $user_course->get_id() ) || CourseProgressStatus::COMPLETED !== $new_status ) {
			return;
		}

		$query = new UserCourseQuery(
			array(
				'course_id' => $user_course->get_course_id(),
				'user_id'   => get_current_user_id(),
			)
		);

		$user_courses = $query->get_user_courses();

		masteriyo_set_notification( $id, current( $user_courses ), $result );
	}

	/**
	 * Schedule order completed notification to student.
	 *
	 * @since 1.7.1
	 *
	 * @param int $order_id
	 * @param \Masteriyo\Models\Order $order
	 */
	public function schedule_completed_order_notification_to_student( $id, $user_course ) {
		if ( ! $user_course ) {
			return;
		}
		$result = masteriyo_get_setting( 'notification.student.completed_order' );
		if ( ! isset( $result ) ) {
			return;
		}

		$course_items = $user_course->get_items( 'course' );

		$data = array();

		foreach ( $course_items as $course_item ) {
			$data[] = array(
				'course_id' => $course_item->get_course_id(),
			);
		}

		foreach ( $data as $data_entry ) {
			$query = new UserCourseQuery(
				array(
					'course_id' => $data_entry['course_id'],
					'user_id'   => $user_course->get_customer_id(),
				)
			);

			$user_courses = $query->get_user_courses();

			masteriyo_set_notification( null, current( $user_courses ), $result );
		}
	}

	/**
	 * Schedule order onhold notification to student.
	 *
	 * @since 1.7.1
	 *
	 * @param int $order_id
	 * @param \Masteriyo\Models\Order $order
	 */
	public function schedule_on_hold_order_notification_to_student( $id, $user_course ) {
		if ( ! $user_course ) {
			return;
		}
		$result = masteriyo_get_setting( 'notification.student.onhold_order' );
		if ( ! isset( $result ) ) {
			return;
		}

		$course_items = $user_course->get_items( 'course' );

		$data = array();

		foreach ( $course_items as $course_item ) {
			$data[] = array(
				'course_id' => $course_item->get_course_id(),
			);
		}

		foreach ( $data as $data_entry ) {
			$query = new UserCourseQuery(
				array(
					'course_id' => $data_entry['course_id'],
					'user_id'   => $user_course->get_customer_id(),
				)
			);

			$user_courses = $query->get_user_courses();

			masteriyo_set_notification( null, current( $user_courses ), $result );
		}
	}

	/**
	 * Schedule order cancelled notification to student.
	 *
	 * @since 1.7.1
	 *
	 * @param int $order_id
	 * @param \Masteriyo\Models\Order $order
	 */
	public function schedule_cancelled_order_notification_to_student( $id, $user_course ) {
		$result = masteriyo_get_setting( 'notification.student.cancelled_order' );
		if ( ! isset( $result ) ) {
			return;
		}
		if ( ! $user_course ) {
			return;
		}

		$course_items = $user_course->get_items( 'course' );

		$data = array();

		foreach ( $course_items as $course_item ) {
			$data[] = array(
				'course_id' => $course_item->get_course_id(),
			);
		}

		foreach ( $data as $data_entry ) {
			$query = new UserCourseQuery(
				array(
					'course_id' => $data_entry['course_id'],
					'user_id'   => $user_course->get_customer_id(),
				)
			);

			$user_courses = $query->get_user_courses();

			masteriyo_set_notification( null, current( $user_courses ), $result );
		}
	}


	/**
	 * Schedule order created notification to student.
	 *
	 * @since 1.7.1
	 *
	 * @param int $order_id
	 * @param \Masteriyo\Models\Order $order
	 */
	public function schedule_order_created_notification_to_student( $user_course ) {
		if ( ! masteriyo_is_current_user_student( $user_course->get_id() ) ) {
			return;
		}
		$result = masteriyo_get_setting( 'notification.student.created_order' );

		if ( ! isset( $result ) ) {
			return;
		}

		$course_items = $user_course->get_items( 'course' );

		$data = array();

		foreach ( $course_items as $course_item ) {
			$data[] = array(
				'course_id' => $course_item->get_course_id(),
			);
		}

		foreach ( $data as $data_entry ) {
			$query = new UserCourseQuery(
				array(
					'course_id' => $data_entry['course_id'],
					'user_id'   => get_current_user_id(),
				)
			);

			$user_courses = $query->get_user_courses();

			masteriyo_set_notification( null, current( $user_courses ), $result );
		}

	}

	/**
	 * This is where the magic happens, within the method you can
	 * access the container and register or retrieve anything
	 * that you need to, but remember, every alias registered
	 * within this method must be declared in the `$provides` array.
	 *
	 * @since 1.4.1
	 */
	public function register() {
		$this->getContainer()->add( 'notification.store', NotificationRepository::class );

		$this->getContainer()->add( 'notification.rest', NotificationsController::class )
			->addArgument( 'permission' );

		$this->getContainer()->add( 'notification', Notification::class )
			->addArgument( 'notification.store' );
	}
}
