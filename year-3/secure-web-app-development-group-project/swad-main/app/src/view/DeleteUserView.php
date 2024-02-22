<?php
/**
 * file to facilitate the deletion of users view. 
 * calls on relevant views and twig files to facilitate the view
 * calls on css & bootstrap
 * class DeleteUserView - returns output
 * @
 * @deleteOutput - successful user deletion view is returned
 * @deleteUserForm - form view is returned
 **/

class DeleteUserView
{
    public function deleteOutput($view, $response, $session_value, $user_id): void
    {
        $view->render($response,
            'delete_output.twig',
            [
                'css_path' => CSS_PATH . 'dashboard.css',
                'bootstrap' => BOOTSTRAP_PATH,
                'header_1' => 'User deletion',
                'header_3' => 'User successfully deleted from database!',
                'paragraph_1' => 'If existed, the user was deleted with the ID of: ',
                'user_id' => $user_id,
                'method' => 'post',
                'action' => 'dashboard',
                'logout_path' => LOGOUT_PATH,
                'username' => $session_value,
            ]);
    }

    public function deleteUserForm($view, $response, $session_value): void
    {
        $view->render($response,
            'delete_user_form.twig',
        [
            'css_path' => CSS_PATH . 'dashboard.css',
            'bootstrap' => BOOTSTRAP_PATH,
            'header_1' => 'User deletion',
            'header_2' => 'Enter a user id to delete from the database',
            'method' => 'post',
            'action_delete_user' => 'delete-user-output',
            'action_dashboard' => 'dashboard',
            'logout_path' => LOGOUT_PATH,
            'username' => $session_value,
        ]);
    }
}
