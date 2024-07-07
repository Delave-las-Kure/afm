<?
add_action('init', 'custom_disable_password_delete');

function custom_disable_password_delete()
{
	if (
		isset($_POST['_um_account_tab']) &&
		$_POST['_um_account_tab'] === 'delete' &&
		isset($_POST['_um_account']) &&
		isset($_POST['um_account_nonce_delete']) &&
		wp_verify_nonce($_POST['um_account_nonce_delete'], 'um_update_account_delete') &&
		!current_user_can('manage_options')
	) {
		UM()->user()->delete(get_current_user_id());
		wp_logout();
		wp_redirect(home_url());
		exit;
	}
}
