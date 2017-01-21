<?php 


/* search user by id */
function SearchUser_func() {
	$user_id = $_POST['user_id'];
	$status = 'FAILED';
	$user = get_user_by('ID', $user_id);

	if ( ! empty( $user ) ) {
		$message = array();
		$message['name'] = $user->data->display_name;
		$message['email'] = $user->data->user_email;
		$message['roles'] = $user->roles;
		$message['avatar'] = get_avatar_url($user->data->ID);
		$status = 'SUCCESS';
	} else {
		$message = 'User not found!';
		$status = 'FAILED';
	}

	echo json_encode(array( 'status' => $status, 'responsetext' => $message ));
	die();
}