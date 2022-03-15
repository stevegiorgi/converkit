<?php

namespace App\Api;

use WP_REST_Controller;


/**
 * REST_API Handler
 */
class Api extends WP_REST_Controller
{

	/**
	 * Request User ID.
	 *
	 * @var integer $user_id;
	 */
	private $user_id = null;

	/**
	 * Request Current User ID.
	 *
	 * @var object $current_user_id;
	 */

	private $current_user_id = null;

	/**
	 * Auto generated Zoom API token header.
	 */
	private $token_header = null;

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		$this->namespace = 'ldd/v2';
		$this->rest_base_users = 'users';
		$this->rest_base_group_users = 'group-users';
		$this->rest_base_administrator_groups = 'groups';
		$this->rest_base_activity = 'activity';
		$this->rest_base_zoom_meetings = 'meetings';
		$this->rest_base_zoom_users = 'zoom-users';
		$this->token_header = 'authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImVTeUJ1Y0RBUTFTNDE5cGxGaDZOR3ciLCJleHAiOjE2MzE1MzQzMjUsImlhdCI6MTYzMDkyOTUyNX0.6T4saNd2RMhE2C_ROHqEIB4w9_k_Fxm4YXgmMrDDTnI';

		$this->rest_base_ck_users = 'ck-users';
		$this->rest_base_ck_tags = 'ck-tags';
		$this->rest_base_ck_user_tags = 'ck-user-tags';
		$this->rest_base_ck_users_by_tag = 'ck-users-by-tag';

		$this->rest_base_ck_sync_data = 'ck-sync-data';

		// $this->rest_sub_base = 'users-course-progress';
	}

	/**
	 * Register the routes
	 *
	 * @return void
	 */
	public function register_routes()
	{

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_users . '/(?P<id>[\d]+)/',
			array(
				array(
					'args'   => array(
						'id' => array(
							'description' => esc_html__('User ID', 'learndash'),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array($this, 'get_items'),
					'permission_callback' => array($this, 'get_items_permissions_check'),
					// 'args'                => $this->get_collection_params(),
				)
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_group_users . '/(?P<id>[\d]+)/',
			array(
				array(
					'args'   => array(
						'id' => array(
							'description' => esc_html__('Group ID', 'learndash'),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_group_users'),
					'permission_callback' => array($this, 'get_items_permissions_check'),
					// 'args'                => $this->get_collection_params(),
				)
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_activity . '/(?P<id>[\d]+)/',
			array(
				array(
					'args'   => array(
						'id' => array(
							'description' => esc_html__('User ID', 'learndash'),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_user_activity'),
					'permission_callback' => array($this, 'get_items_permissions_check'),
					// 'args'                => $this->get_collection_params(),
				)
			)
		);


		// ConvertKit Endpoints



		// List Subscribers
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_ck_users . '/',
			array(
				array(
					'args'   => array(
						'get_all' => array(
							'description' => esc_html__('get_all', 'convertkit'),
							'required'    => false,
							'type'				=> 'boolean'
						),
						'page' => array(
							'description' => esc_html__('page', 'convertkit'),
							'required'    => false,
							'type'				=> 'integer'
						),
						'from_date' => array(
							'description' => esc_html__('from_date', 'convertkit'),
							'required'    => false,
							'type'        => 'string',
						),
						'to_date' => array(
							'description' => esc_html__('to_date', 'convertkit'),
							'required'    => false,
							'type'        => 'string',
						),
						'sort_order' => array(
							'description' => esc_html__('sort_order', 'convertkit'),
							'required'    => false,
							'type'        => 'string',
						),
						'sort_field' => array(
							'description' => esc_html__('sort_field', 'convertkit'),
							'required'    => false,
							'type'        => 'string',
						),
					),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_converkit_users'),
				)
			)
		);


		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_ck_tags . '/',
			array(
				array(
					// 'args'   => array(
					// 	'user_id' => array(
					// 		'description' => esc_html__('User ID', 'converkit'),
					// 		'required'    => true,
					// 		'type'        => 'integer',
					// 	),
					// ),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_convertkit_tags'),
				)
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_ck_user_tags . '/',
			array(
				array(
					'args'   => array(
						'user_id' => array(
							'description' => esc_html__('User ID', 'converkit'),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_convertkit_tags_by_uid'),
				)
			)
		);


		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_ck_users_by_tag . '/',
			array(
				array(
					'args'   => array(
						'tag_id' => array(
							'description' => esc_html__('Tag ID', 'converkit'),
							'required'    => true,
							'type'        => 'integer',
						),
						'page' => array(
							'description' => esc_html__('Page', 'converkit'),
							'required' 		=> false,
							'type'				=> 'integer',
						)
					),
					'methods'             => 'GET',
					'callback'            => array($this, 'get_convertkit_users_by_tag'),
				)
			)
		);


		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_ck_sync_data . '/',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array($this, 'sync_converkit_data'),
				)
			)
		);











		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_users . '/',
			array(
				'callback'            => array($this, 'get_zoom_user_email'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/zoom-meetings/',
			array(
				'callback'            => array($this, 'get_zoom_meetings'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/user-meetings/',
			array(
				'callback'            => array($this, 'get_zoom_user_meetings'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/zoom-meeting/',
			array(
				'callback'            => array($this, 'get_zoom_meeting'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/zoom-meeting-participants/',
			array(
				'callback'            => array($this, 'get_zoom_meeting_participants'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/meeting-history/',
			array(
				'callback'            => array($this, 'get_zoom_meeting_history'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_zoom_meetings . '/zoom-meeting-participant-details/',
			array(
				'callback'            => array($this, 'get_zoom_meeting_participant_details'),
				// array(
				// 	// 'args'   => array(
				// 	// 	'id' => array(
				// 	// 		'description' => esc_html__('Zoom Users', 'learndash'),
				// 	// 		'required'    => true,
				// 	// 		'type'        => 'integer',
				// 	// 	),
				// 	// ),
				// 	// 'methods'             => 'GET',

				// 	// 'permission_callback' => array($this, 'get_items_permissions_check'),
				// 	// 'args'                => $this->get_collection_params(),
				// )
			)
		);

		// register_rest_route(
		// 	$this->namespace,
		// 	'/' . $this->rest_base_administrator_groups . '/(?P<id>[\d]+)/',

		// 	array(
		// 		array(
		// 			'args'   => array(
		// 				'id' => array(
		// 					'description' => esc_html__('Group Leader ID', 'learndash'),
		// 					'required'    => true,
		// 					'type'        => 'integer',
		// 				),
		// 			),
		// 			'methods'             => \WP_REST_Server::READABLE,
		// 			'callback'            => array($this, 'get_administrators_group_ids'),
		// 			'permission_callback' => array($this, 'get_items_permissions_check'),
		// 			// 'args'                => $this->get_collection_params(),
		// 		)
		// 	)
		// );


		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base_administrator_groups,

			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array($this, 'get_administrators_group_ids'),
				'permission_callback' => array($this, 'get_items_permissions_check'),
			)
		);
	}

	/**
	 * Retrieves overall course progression.
	 *
	 */
	public static function get_completion_percentage($user_id)
	{

		$study_units = new \WP_query(
			array(
				'post_type'			=>	'sfwd-courses',
				'orderby'			=>	'',
				// 'post__not_in'      =>  $exclude_ids,
				'posts_per_page'	=>	-1
			)
		);

		$all_courses = array(); // update to SQL query once we can see which table/column contains total steps
		if (!empty($study_units)) {
			foreach ($study_units->posts as $course_id) {
				$course_id = $course_id->ID;
				// foreach( array_reverse($user_course_id) as $course_id ) {
				$all_courses[] = learndash_course_progress(
					array(
						'user_id'   => $user_id,
						'course_id' => $course_id,
						'array'     => true
					)
				);
			}
		}

		$overall_steps = 0; // overall course steps
		foreach ($all_courses as $key => $all) {
			$overall_steps += $all['total'];
		}

		$steps_completed = array();
		if (!empty($study_units)) {
			foreach ($study_units->posts as $course_id) {
				$course_id = $course_id->ID;
				$steps_completed[] = learndash_course_progress(
					array(
						'user_id'   => $user_id,
						'course_id' => $course_id,
						'array'     => true
					)
				);
			}
		}

		$completed = 0;
		$overall_completion = 0;
		foreach ($steps_completed as $steps) {
			$completed += (int)$steps['completed'];
			if ($completed != 0) {
				$overall_completion = ($completed / $overall_steps) * 100;
			} else {
				$overall_completion = 0;
			}
		}

		return round($overall_completion, 2);
	}

	/**
	 * Retrieves a collection of course progress data.
	 *
	 */
	public static function get_course_activity($user_id)
	{
		$user_course_progress = get_user_meta($user_id, '_sfwd-course_progress', true);

		$course_data = [];
		$lesson_data = [];
		$course_id = 0;
		$lesson_id = 0;

		if ($user_course_progress) {

			foreach ($user_course_progress as $course_id => $course) {

				$user_course_started = get_user_meta($user_id, 'course_' . $course_id . '_access_from', true);
				if (!empty($user_course_started)) {
					$user_course_progress['date_started'] = gmdate('Y-m-d h:i:s', $user_course_started);
				}

				$user_course_completed = get_user_meta($user_id, 'course_completed_' . $course_id, true);
				if (!empty($user_course_completed)) {
					$user_course_progress['date_completed'] = gmdate('Y-m-d h:i:s', $user_course_completed);
				}

				$lesson_data[$course_id] = array(
					'course_id' 			=> $course_id,
					'course_title' 		=> get_the_title($course_id),
					'completed' 			=> (isset($course['completed']) && !empty($course['completed'])) ? $course['completed'] : 0,
					'course_status' 	=> learndash_course_status($course_id, $user_id),
					'date_started' 		=> (isset($user_course_progress['date_started'])) ? $user_course_progress['date_started'] : '--',
					'date_completed' 	=> (isset($user_course_progress['date_completed'])) ? $user_course_progress['date_completed'] : '--',
					'total' 					=> (isset($course['total']) && !empty($course['total'])) ? $course['total'] : '',
					'last_id' 				=> (isset($course['last_id']) && !empty($course['last_id'])) ? $course['last_id'] : '',
					'course_progress' => learndash_course_progress(
						array(
							'user_id'   	=> $user_id,
							'course_id' 	=> $course_id,
							'array'     	=> true,
						)
					),
				);

				if (isset($course['lessons']) && !empty($course['lessons'])) {
					foreach ($course['lessons'] as $lesson_id => $lesson) {

						$lesson_args = array(
							'course_id'     => $course_id,
							'user_id'       => $user_id,
							'post_id'       => $lesson_id,
							'activity_type' => 'lesson',
						);

						$lesson_activity = learndash_get_user_activity($lesson_args);

						// foreach($lesson_activity as $lesson) {
						// 	$lesson_activity = array(
						// 		'activity_started' => gmdate( 'Y-m-d h:i:s', $lesson->activity_started ),
						// 		'activity_completed' => gmdate( 'Y-m-d h:i:s', $lesson->activity_completed ),
						// 	);
						// }

						$lesson_data[$course_id]['lessons'][] = array(
							'lesson_id' 		=> $lesson_id,
							'lesson_title' 		=> get_the_title($lesson_id),
							'lesson_activity' 	=> $lesson_activity,
						);
					}
				}
			}

			foreach ($lesson_data as $data) {
				$course_data[] = $data; // iterate over data object to switch it to an array; json_decode/encode wasn't working because of the keyed data...what am I not understanding?
			}
			return $course_data;
		}
	}









	/**
	 * Retrieves a collection of quiz progress data.
	 *
	 */
	public static function get_quiz_activity($user_id)
	{

		$quiz_attempts = get_user_meta($user_id, '_sfwd-quizzes', true);
		$quiz_data = [];

		if (!empty($quiz_attempts) && is_array($quiz_attempts)) {
			$quiz_data = [];

			foreach ($quiz_attempts as $quiz_attempt) {

				$quiz_id = $quiz_attempt['quiz'];
				$attempts = 0;
				foreach ($quiz_attempts as $quiz) {
					if ($quiz['quiz'] == $quiz_id) {
						$attempts++;
					}
				}

				$timestamp = '';
				$started = '';
				$completed = '';
				$percentage = '';
				$pass = $quiz['pass'];

				$quiz = get_post($quiz_attempt['quiz']);
				$title = $quiz->post_title;
				$date_format = date('Y-m-d H:i:s');

				// Convert timestamps into date/time ('F j, Y H:i:s')
				$timestamp = get_date_from_gmt(date('Y-m-d H:i:s', $quiz_attempt['time']));
				$started = get_date_from_gmt(date('Y-m-d H:i:s', $quiz_attempt['started']));
				$completed = get_date_from_gmt(date('Y-m-d H:i:s', $quiz_attempt['completed']));
				$percentage = !empty($quiz_attempt['percentage']) ? $quiz_attempt['percentage'] : (!empty($quiz_attempt['count']) ? $quiz_attempt['score'] * 100 / $quiz_attempt['count'] : 0);

				$quiz_data[] = array(
					'quiz_id'			=> $quiz_id,
					'quiz_title'        => $title,
					// 'link'          	=> get_permalink( $quiz->ID ),
					'quiz_score'        => $percentage,
					'pass'				=> $pass,
					'attempts'      	=> $attempts,
					'category_id'   	=> $quiz_attempt['course'],
					'lesson_id'     	=> $quiz_attempt['lesson'],
					// 'duration'      	=> PostHelper::seconds_to_time($quiz_attempt['timespent']),
					'timespent'     	=> gmdate("H:i:s", $quiz_attempt['timespent']),
					'date'          	=> $quiz_attempt['time'],
					'quiz_started'      => $started,
					'quiz_completed'    => $completed,
				);
			}
		}

		if (!empty($quiz_data)) {
			// $quiz_data = call_user_func_array('array_merge', $quiz_data);

			$sort_quizzes = [];
			foreach ($quiz_data as $key => $q) {
				if (!isset($sort_quizzes[$q['quiz_id']])) {
					$sort_quizzes[$q['quiz_id']] = $q;
					$sort_quizzes[$q['quiz_id']]['key'] = $key;
				} else {

					if (date_create_from_format('Y-m-d', $sort_quizzes[$q['quiz_id']]['date']) < date_create_from_format('Y-m-d', $q['date'])) {
						$sort_quizzes[$q['quiz_id']] = $q;
						$sort_quizzes[$q['quiz_id']]['key'] = $key;
					}
				}
			}
			$quiz_data = [];
			foreach ($sort_quizzes as $key => $q) {
				$quiz_data[] = $q;
			}
		}

		return $quiz_data;
	}

	/**
	 * Retrieves a collection of user data.
	 *
	 */
	public function get_user_data($user_id)
	{

		$user_info = get_userdata($user_id);

		$lesson_count = 0;
		$course_activity = Api::get_course_activity($user_id);
		if (!empty($course_activity)) {
			foreach ($course_activity as $course) {
				$lesson_count += $course['course_progress']['completed'];
			}
		}

		// $quiz_data = Api::get_quiz_activity($user_id);
		// $quiz_data = call_user_func_array('array_merge', $quiz_data);

		// $sort_quizzes = [];
		// foreach ( $quiz_data as $key => $q) {
		// 	if ( !isset( $sort_quizzes[ $q['id'] ] ) ){
		// 		$sort_quizzes[ $q['id'] ] = $q;
		// 		$sort_quizzes[ $q['id'] ]['key'] = $key;
		// 	} else {

		// 		if ( date_create_from_format('m/d/Y', $sort_quizzes[ $q['id'] ]['date']) < date_create_from_format('m/d/Y', $q['date'] ) ){
		// 			$sort_quizzes[ $q['id'] ] = $q;
		// 			$sort_quizzes[ $q['id'] ]['key'] = $key;
		// 		}
		// 	}
		// }
		// $quiz_data = [];
		// foreach ($sort_quizzes as $key => $q){
		// 	$quiz_data[$key] = $q;
		// }

		$last_login = null;
		$activities = null;

		$last_login_timestamp = (int)get_user_meta($user_id, 'learndash-last-login', true); // old method

		$activities = Api::get_activity($user_id, 'all'); // pull all user activity

		end($activities);
		$last_activity_key = key($activities); // look at the latest activity; are we certian this is looking at the latest activity maybe these records are not in chrnological order

		$latest_activity_title = $activities[$last_activity_key]['activity_title'];

		if ($activities[$last_activity_key]['activity_status'] === 1) { // check if the activity has been completed
			$last_login = $activities[$last_activity_key]['activity_completed']; // if the activity has been completed, set the last login to the activity completion date
		} else {
			$last_login = $activities[$last_activity_key]['activity_started']; // if the activity has not been completed, set the last login to the activity start date
		}

		if (empty($activities)) { // if there are no activites, let's check our old method
			if (!empty($last_login_timestamp)) {
				$last_login = gmdate('Y-m-d H:i:s', $last_login_timestamp); // if we have a timestamp, set our last login to it
			} else {
				$last_login = 'Never logged in.';
			}
		}

		$items = [
			'id' => $user_id,
			'avatar' => get_avatar_url($user_id),
			'first_name' => $user_info->first_name,
			'last_name' => $user_info->last_name,
			'display_name' => $user_info->last_name . ', ' . $user_info->first_name,
			'email_address' => $user_info->user_email,
			'groups' => learndash_get_users_group_ids($user_id),
			'group_leader' => learndash_is_group_leader_user($user_id),
			'latest_activity' => htmlspecialchars_decode($latest_activity_title),
			'lessons_completed' => $lesson_count,
			'overall_completion' => Api::get_completion_percentage($user_id),
			'last_login' => $last_login,

			// 'last_login' => (!empty($last_login_timestamp)) ? gmdate('Y-m-d H:i:s', $last_login_timestamp) : 'Not yet logged in.',
			// 'courses' => Api::get_course_activity($user_id),
			// 'quizzes' => $quiz_data,
		];

		return $items;
	}








	public static function get_activity($user_id, $activity_type)
	{
		global $wpdb;

		$table = $wpdb->prefix . "learndash_user_activity";
		$sql = $wpdb->prepare("SELECT * FROM {$table} WHERE {$table}.user_id = '$user_id'");

		$data = $wpdb->get_results($sql);

		$activities = array();
		$activity_data = array();

		if ($activity_type === 'all') {
			foreach ($data as $activity) {
				$activities[] = $activity;
			}
		} else {
			foreach ($data as $activity) {
				if ($activity->activity_type === $activity_type) {
					$activities[] = $activity;
				}
			}
		}

		foreach ($activities as $key => $activity) {
			$activity_data[] = array(
				'activity_id' => $activity->activity_id,
				'user_id' => $activity->user_id,
				'post_id' => $activity->post_id,
				'activity_title' => get_the_title($activity->post_id),
				'activity_type' => $activity->activity_type,
				'activity_status' => $activity->activity_status,
				'activity_started' => gmdate('Y-m-d h:i:s', $activity->activity_started),
				'activity_completed' => ($activity->activity_completed === null || $activity->activity_completed == 0) ? null : gmdate('Y-m-d h:i:s', $activity->activity_completed),
				'activity_duration' => ($activity->activity_completed === null || $activity->activity_completed == 0) ? null : strtotime(gmdate('Y-m-d h:i:s', $activity->activity_completed)) - strtotime(gmdate('Y-m-d h:i:s', $activity->activity_started)),
				'activity_updated' => $activity->activity_update,
				'course_id' => $activity->course_id,
				'course_title' => get_the_title($activity->course_id),
				'meta_data' => Api::get_activity_meta($activity->activity_id),
			);
		}

		return $activity_data;
	}

	public static function get_activity_meta($activity_id)
	{
		global $wpdb;

		$table = $wpdb->prefix . "learndash_user_activity_meta";
		$sql = $wpdb->prepare("SELECT * FROM {$table} WHERE {$table}.activity_id = '$activity_id'");

		$data = $wpdb->get_results($sql);

		return $data;
	}

	public function get_user_activity($request)
	{

		$user_id = $request['id'];
		$user_info = get_userdata($user_id);

		// $last_login_timestamp = get_user_meta($user_id, 'learndash-last-login', true);		
		// $last_course_step = htmlspecialchars_decode(learndash_user_course_last_step($user_id));
		// $last_course_step_title = htmlspecialchars_decode(get_the_title($last_course_step));

		// $lesson_count = 0;
		// $course_activity = Api::get_course_activity($user_id);
		// if(!empty($course_activity)) {
		// 	foreach($course_activity as $course) {
		// 		$lesson_count += $course['completed'];
		// 	}
		// }

		// $course_data = Api::get_course_activity($user_id) ? Api::get_course_activity($user_id) : [];
		// $quiz_data = Api::get_quiz_activity($user_id);

		// $activity = Api::get_activity($user_id, 'access') ? $activity = Api::get_activity($user_id, 'access') : [];

		$data = array(
			'id' => $user_id,
			'first_name' => $user_info->first_name,
			'last_name' => $user_info->last_name,
			'display_name' => $user_info->last_name . ', ' . $user_info->first_name,
			'course_activities' => Api::get_activity($user_id, 'course') ? $activity = Api::get_activity($user_id, 'course') : [],
			'lesson_activities' => Api::get_activity($user_id, 'lesson') ? $activity = Api::get_activity($user_id, 'lesson') : [],
			'topic_activites' => Api::get_activity($user_id, 'topic') ? $activity = Api::get_activity($user_id, 'topic') : [],
			'quiz_activities' => Api::get_activity($user_id, 'quiz') ? $activity = Api::get_activity($user_id, 'quiz') : [],
			// 'email_address' => $user_info->user_email,
			// 'latest_activity' => htmlspecialchars_decode($last_course_step_title),
			// 'lessons_completed' => $lesson_count,
			// 'overall_completion' => Api::get_completion_percentage($user_id),
			// 'last_login' => (!empty($last_login_timestamp)) ? gmdate('Y-m-d H:i:s', $last_login_timestamp) : 'Not yet logged in.',
			// 'groups' => learndash_get_users_group_ids($user_id),
			// 'courses' => $course_data,
			// 'quizzes' => $quiz_data,
		);

		$response = rest_ensure_response($data);

		return $response;
	}





	/**
	 * Retrieves Zoom Users
	 * 
	 */

	public function get_zoom_user_email($request)
	{

		$email = $request['email'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.zoom.us/v2/users/$email",
			// CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			// CURLOPT_URL => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	/**
	 * Retrieves Zoom Users
	 * 
	 */

	public function get_zoom_meetings($request)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
			CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			//  => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	public function get_zoom_meeting_instances($request)
	{
		$curl = curl_init();

		$meeting_id = $request['meeting_id'];

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
			// CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	public function get_zoom_user_meetings($request)
	{
		$curl = curl_init();
		$user = $request["user"];

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
			CURLOPT_URL => "https://api.zoom.us/v2/users/$user/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			//  => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	public function get_zoom_meeting($request)
	{
		$curl = curl_init();
		$uuid = $request["uuid"];

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
			// CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			//  => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
			CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	public function get_zoom_meeting_participants($request)
	{
		$curl = curl_init();
		$id = $request["id"];

		curl_setopt_array($curl, array(
			// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
			// CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
			// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
			//  => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
			CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${id}/participants",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				$this->token_header,
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$error = curl_error($curl);

		curl_close($curl);

		// $response = rest_ensure_response($data);

		return json_decode($response);
	}

	public function get_zoom_meeting_participant_details($request)
	{
		$uuid = $request["uuid"];
		$next_token = $request["next_token"];
		$participants = array();

		$continue = true;
		$next_token = null;

		$cache_seconds = 60 * 60 * 24; // a day"

		// $wp_object_cache->delete('participants', 'participant_details');
		// delete_transient('participants');

		$participants_cache = get_transient('participants_' . $uuid);

		// $participants_cache = wp_cache_get('participants' . $uuid, 'participant_details');

		if (empty($participants_cache)) {

			while ($continue) {

				$curl = curl_init();

				$params = array();
				if (!is_null($next_token)) {
					$params['next_page_token'] = $next_token;
				}

				curl_setopt_array($curl, array(
					// CURLOPT_URL => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
					// CURLOPT_URL => "https://api.zoom.us/v2/users/A1cHIBo6TQyXgxqerfJyCw/meetings",
					// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${uuid}/participants",
					// CURLOPT_URL => "https://api.zoom.us/v2/past_meetings/${meeting_id}/instances",
					//  => "https://api.zoom.us/v2/report/meetings/{$meeting_id}/participants",
					CURLOPT_URL => "https://api.zoom.us/v2/report/meetings/${uuid}/participants?" . http_build_query($params),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
						$this->token_header,
						"content-type: application/json",
					),
				));

				$response = json_decode(curl_exec($curl));
				$next_token = $response->next_page_token;
				$continue = !empty($response->next_page_token);
				if (is_array($response->participants)) {
					$participants = array_merge($participants, $response->participants);
				}
				// $error = curl_error($curl);

				curl_close($curl);
			}

			// wp_cache_set('participants' . $uuid, $participants, 'participant_details', $cache_seconds);
		} else {

			$participants = $participants_cache;
		}

		// $response = rest_ensure_response($data);
		set_transient('participants_' . $uuid, $participants, $cache_seconds);

		return $participants;
	}

	public function get_zoom_meeting_history($request)
	{
		$cache_seconds = 60 * 60 * 24; // a day"
		global $wp_object_cache;

		/* function base64url_encode($data)
		{
			// Encode $data to Base64 string
			$b64 = base64_encode($data);

			// Valid result? Otherwise, return FALSE, as the base64_encode() function does
			if ($b64 === false) {
				return false;
			}

			// Convert Base64 to Base64URL by replacing "+" with "-" and "/" with "_"
			$url = strtr($b64, '+/', '-_');

			// Remove padding character from the end of line and return the Base64URL result
			return rtrim($url, '=');
		}
		function base64url_decode($data, $strict = false)
		{
			// Convert Base64URL to Base64 by replacing "-" with "+" and "_" with "/"
			$b64 = strtr($data, '-_', '+/');

			// Decode Base64 string and return the original data
			return base64_decode($b64, $strict);
		}

		define("API_KEY", "eSyBucDAQ1S419plFh6NGw");
		define("API_SECRET", "AY1IBBnmIvkEH4JbP0FtKrc2Zg0250MGnSh2");
		$header = '{"alg":"HS256","typ":"JWT"}';
		$payload = '{"iss":"' . API_KEY . '","exp":' . (time() + 50) . '}';
		$signature = hash_hmac("sha256", base64url_encode($header) . "." . base64url_encode($payload), API_SECRET, false);
		$this->token_header = "authorization: Bearer " . (base64url_encode($header) . "." . base64url_encode($payload) . "." . $signature); */

		$email = $request['email'];

		if (is_null($email)) {
			return 'Provide email';
		}

		global $wpdb;

		$table_name = $wpdb->prefix . 'zoom_uuids';

		$prepare = $wpdb->prepare("SELECT uuid FROM {$table_name}");
		$uuids = $wpdb->get_col($prepare);

		if (count($uuids) < 1) {
			return 'No UUIDs on database';
		}


		/* $meetingsResponse = $this->get_zoom_user_meetings(array('user' => $email));

		$meetings = $meetingsResponse->meetings;

		$finalMeeting = null;
		foreach ($meetings as $meeting) {
			$topic = $meeting->topic;
			if (strpos($topic, "Coach's Corner") !== false) {
				$finalMeeting = $meeting;
			}
		}

		if (is_null($finalMeeting)) {
			return "No Coach's Corner found";
		}

		$meetingId = $finalMeeting->id;
		// $meetingDateBeginning = substr($finalMeeting->start_time, 0, 10);

		$instancesResponse = $this->get_zoom_meeting_instances(array('meeting_id' => $meetingId)); 
		$instances = $instancesResponse->meetings; */

		// $finalInstance = null;

		$result_array = array();

		foreach ($uuids as $uuid) {
			// $start_time = $instance->start_time;

			/* if (strpos($start_time, $meetingDateBeginning) !== false) {
				$finalInstance = $instance;
			} */

			$participants = $this->get_zoom_meeting_participant_details(array('uuid' => $uuid));

			foreach ($participants as $participant) {
				$participantEmail = $participant->user_email;
				if ($participantEmail == strtolower($email)) {
					$result_array[] = $participant;
				}
			}
		}

		/*if ($finalInstance == null) {
			return 'Cannot find an instance';
		}*/

		// $uuid = $finalInstance->uuid;

		// $details_response = $this->get_zoom_meeting_participant_details(array('uuid' => $uuid));

		return $result_array;
	}





	/**
	 * Retrieves a collection of items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_items($request)
	{

		$user_id = $request['id'];
		$user_info = get_userdata($user_id);

		if ($user_info) {

			$last_login_timestamp = get_user_meta($user_id, 'learndash-last-login', true);
			$last_course_step = htmlspecialchars_decode(learndash_user_course_last_step($user_id));
			$last_course_step_title = htmlspecialchars_decode(get_the_title($last_course_step));


			$lessons_completed_count = 0;
			$course_activity = Api::get_course_activity($user_id);
			if (!empty($course_activity)) {
				foreach ($course_activity as $course) {
					$lessons_completed_count += $course['course_progress']['completed'];
				}
			}

			$lessons_total_count = 0;
			$course_activity = Api::get_course_activity($user_id);
			if (!empty($course_activity)) {
				foreach ($course_activity as $course) {
					$lessons_total_count += $course['course_progress']['total'];
				}
			}

			if ($lessons_completed_count &&  $lessons_total_count) {
				$total_course_percentage = ($lessons_completed_count / $lessons_total_count * 100);
			} else {
				$total_course_percentage = '--';
			}

			// $total_lesson_count = 0;
			// $course_activity = Api::get_course_activity($user_id);
			// if (!empty($course_activity)) {
			// 	foreach ($course_activity as $course) {
			// 		$lesson_count += $course['completed'];
			// 	}
			// }

			$course_data = Api::get_course_activity($user_id) ? Api::get_course_activity($user_id) : [];
			$quiz_data = Api::get_quiz_activity($user_id);

			$items = array(
				'id' => $user_id,
				'first_name' => $user_info->first_name,
				'last_name' => $user_info->last_name,
				'display_name' => $user_info->last_name . ', ' . $user_info->first_name,
				'email_address' => $user_info->user_email,
				'latest_activity' => htmlspecialchars_decode($last_course_step_title),
				'lessons_completed' => $lessons_completed_count,
				'lessons_total' => $lessons_total_count,
				'overall_completion' => $total_course_percentage,
				// 'overall_completion' => Api::get_completion_percentage($user_id),
				// 'last_login' => (!empty($last_login_timestamp)) ? gmdate('Y-m-d H:i:s', $last_login_timestamp) : 'Not yet logged in.',
				'groups' => learndash_get_users_group_ids($user_id),
				'courses' => $course_data,
				'quizzes' => $quiz_data,
				'test' => Api::get_course_activity($user_id),
			);

			$groups = array();
			foreach ($items['groups'] as $group) {
				$groups[] = array(
					'group_id' => $group,
					'group_title' => get_the_title($group),
				);
			}

			$items['groups'] = $groups;

			$response = rest_ensure_response($items);

			return $response;
		}
	}

	public function get_group_users($request)
	{

		$group_users = learndash_get_groups_user_ids($request['id']);
		$user_data = array();

		foreach ($group_users as $group_user) {
			$user_data = Api::get_user_data($group_user);
			$items[] = $user_data;
		}

		$response = rest_ensure_response($items);

		return $response;
	}

	public function get_administrators_group_ids($request)
	{
		$current_user_id = get_current_user_id();
		$group_ids = learndash_get_administrators_group_ids($current_user_id);
		$groups = [];

		foreach ($group_ids as $group) {
			$groups[] = array(
				'id' => $group,
				'name' => get_the_title($group),
			);
		}

		$items = $groups;

		$response = rest_ensure_response($items);

		return $response;
	}

	/**
	 * Checks if a given request has access to read the items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check($request)
	{

		// $user_id = $request['id'];

		// if ( learndash_is_admin_user() ) {
		// 	return true;
		// } elseif ( get_current_user_id() === $user_id ) {
		// 	return true;
		// } elseif ( learndash_is_group_leader_user() ) {
		// 	if ( learndash_is_group_leader_of_user( get_current_user_id(), $user_id ) ) {
		// 		return true;
		// 	}
		// }

		return true;
	}

	/**
	 * Retrieves the query params for the items collection.
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params()
	{
		return [];
	}


	/**
	 * 
	 * Get ConverKit Tags
	 * 
	 */


	// TODO: Create a cronjob for this functionality

	public function sync_converkit_data()
	{

		global $wpdb;
		$api_key = "8xjZlAPwIpU62U7SQjjS-Q";

		$user_id = '';
		$data = array();

		$users = get_transient('ck_users');
		// delete_transient('cl_data');
		// die();

		$cache_seconds = 60 * 60 * 24;
		// $limit = count($users);

		foreach ($users as $key => $user) {

			if ($key % 100 == 0 && $key > 0) {
				sleep(60);
				echo '90 API calls reached; waiting 60 seconds.';
			}

			$curl = curl_init();
			$user_id = $user->id;

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.convertkit.com/v3/subscribers/${user_id}/tags?api_key=${api_key}",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"content-type: application/json",
				),
			));

			$response = json_decode(curl_exec($curl)); // tags

			$user = array(
				'ck_id' => $user->id,
				'first_name' => $user->first_name,
				'email_address' => $user->email_address,
				'state' => $user->state,
				'created_at' => $user->created_at,
				'fields' => $user->fields,
				'tags' => $response->tags,
			);

			curl_close($curl);

			array_push($data, $user);

			$table_name = $wpdb->prefix . 'ck_data';
			$existing_record = $wpdb->get_results("SELECT * FROM $table_name WHERE ck_id = '" . $user['ck_id'] . "'");
			if (count($existing_record) === 0) {
				$wpdb->insert(
					$table_name,
					array(
						'ck_id' => $user['ck_id'],
						'first_name' => $user['first_name'],
						'email_address' => $user['email_address'],
						'state' => $user['state'],
						'created_at' => $user['created_at'],
						'fields' => serialize($user['fields']),
						'tags' => serialize($user['tags']),
					)
				);
			}
		}

		set_transient('ck_data', $data, $cache_seconds);
	}







	public function get_converkit_all_users()
	{

		global $wpdb;

		$data = get_transient('ck_data');

		return $data;
	}





	public function get_converkit_users($request)
	{

		if ($request["get_all"] === true) {
			$data = $this->get_converkit_all_users();
			return $data;
		}

		$api_secret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";

		// Parameter variables
		$current_page = $request["page"] ?? 1;
		$from_date = $request["from_date"] ?? '2022-03-01'; // ex: 2020-02-28
		$to_date = $request["to_date"] ?? date('Y-m-d');
		$sort_order = $request['sort_order'] ?? 'desc';
		$sort_field = $request['sort_field'] ?? '';

		$tags = array();

		$continue = true;
		$cache_seconds = 60 * 60 * 24;

		$tags_cache = get_transient('ck_users');
		// delete_transient('ck_users');
		// die();

		$total_pages = null;


		if (empty($tags_cache)) {
			while ($continue) {
				$curl = curl_init();
				$params = array();

				$params['page'] = $current_page;
				$params['from_date'] = $from_date;
				$params['to_date'] = $to_date;
				$params['sort_order'] = $sort_order;
				$params['sort_field'] = $sort_field;

				print_r($params);
				echo (http_build_query($params));

				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.convertkit.com/v3/subscribers?api_secret=${api_secret}&" . http_build_query($params),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
						"content-type: application/json",
					),
				));

				$response = json_decode(curl_exec($curl));

				$current_page = $response->page;
				$total_pages = $response->total_pages;

				if ($current_page <= $total_pages) {
					$current_page += 1;
				}

				$continue = $current_page <= $total_pages ? true : false;

				if (is_array($response->subscribers)) {
					$tags = array_merge($tags, $response->subscribers);
				}

				// $error = curl_error($curl);

				curl_close($curl);
			}

			// $response = $tags;

		} else {

			$tags = $tags_cache;
			$response = $tags;
		}

		// $response = rest_ensure_response($data);
		set_transient('ck_users', $tags, $cache_seconds);


		return $response;
	}



	public function get_convertkit_tags()
	{

		$api_key = "8xjZlAPwIpU62U7SQjjS-Q";
		$tags = array();

		$cache_seconds = 60 * 60 * 24;
		$tags_cache = get_transient('ck_tags');

		if (empty($tags_cache)) {
			$curl = curl_init();
			// $params = array();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.convertkit.com/v3/tags?api_key=${api_key}",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					// $this->token_header,
					"content-type: application/json",
				),
			));

			$response = json_decode(curl_exec($curl));
			if (is_array($response->tags)) {
				$tags = array_merge($tags, $response->tags);
			}
			// $error = curl_error($curl);

			curl_close($curl);
		} else {

			$tags = $tags_cache;
			$response = $tags;
		}

		// $response = rest_ensure_response($data);
		set_transient('ck_tags', $tags, $cache_seconds);

		return $response;
	}


	/**
	 * 
	 * Retrieve ConvertKit Tags by User ID
	 * 
	 */

	public function get_convertkit_tags_by_uid($request)
	{

		// $api_secret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
		$api_key = "8xjZlAPwIpU62U7SQjjS-Q";
		$user_id = $request["user_id"];
		// $next_token = $request["page"];
		$tags = array();

		// $continue = true;
		// $next_token = null;

		$cache_seconds = 60 * 60 * 24;
		$tags_cache = get_transient('ck_user_tags_' . $user_id);

		// $tags_cache = wp_cache_get('tags' . $uuid, 'tags_details');

		if (empty($tags_cache)) {

			// while ($continue) {

			$curl = curl_init();

			// $params = array();
			// if (!is_null($next_token)) {
			// 	$params['page'] = $next_token;
			// }

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.convertkit.com/v3/subscribers/${user_id}/tags?api_key=${api_key}",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					// $this->token_header,
					"content-type: application/json",
				),
			));

			$response = json_decode(curl_exec($curl));
			// $next_token = $response->page;
			// $continue = !empty($response->page);
			if (is_array($response->tags)) {
				$tags = array_merge($tags, $response->tags);
			}
			// $error = curl_error($curl);

			curl_close($curl);
			// }

			// wp_cache_set('tags' . $uuid, $tags, 'tag_details', $cache_seconds);
		} else {

			$tags = $tags_cache;
			$response = $tags;
		}

		// $response = rest_ensure_response($data);
		set_transient('ck_user_tags_' . $user_id, $tags, $cache_seconds);

		return $response;
	}




	/**
	 * 
	 * Retrieve all users by tag
	 * 
	 */

	// Some API calls require the api_secret parameter. All calls that require api_key also work with api_secret, there's no need to use both. This key grants access to sensitive data and actions on your subscribers. You should treat it as your password and do not use it in any client-side code (usually that means JavaScript).



	public function get_convertkit_users_by_tag($request)
	{

		$api_secret = "1cddU-Wg7MSePN3JYZqa3G4pGs13I9fQfs1aLsEINbg";
		$tag_id = $request["tag_id"];
		$current_page = $request["page"];
		$tags = array();
		$continue = true;
		$cache_seconds = 60 * 60 * 24;

		$tags_cache = get_transient('ck_tag_' . $tag_id . '_users');
		// delete_transient('ck_tag_' . $tag_id . '_users');
		// die();

		$total_pages = null;
		if (empty($tags_cache)) {
			while ($continue) {
				$curl = curl_init();
				$params = array();

				$params['page'] = $current_page;

				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.convertkit.com/v3/tags/${tag_id}/subscriptions?api_secret=${api_secret}&" . http_build_query($params),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
						"content-type: application/json",
					),
				));

				$response = json_decode(curl_exec($curl));

				$current_page = $response->page;
				$total_pages = $response->total_pages;

				if ($current_page <= $total_pages) {
					$current_page += 1;
				}

				$continue = $current_page <= $total_pages ? true : false;

				if (is_array($response->subscriptions)) {
					$tags = array_merge($tags, $response->subscriptions);
				}

				// $error = curl_error($curl);

				curl_close($curl);
			}

			$response = $tags;

			// wp_cache_set('tags' . $uuid, $tags, 'tag_details', $cache_seconds);
		} else {

			$tags = $tags_cache;
			$response = $tags;
		}

		// $response = rest_ensure_response($data);
		set_transient('ck_tag_' . $tag_id . '_users', $tags, $cache_seconds);

		return $response;
	}
}
