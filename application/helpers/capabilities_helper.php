<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function skd_roles() {

    $ci =& get_instance();
 
    if ( ! isset( $ci->roles  ) ) {
        $ci->roles = new SKD_Roles();
    }

    return $ci->roles;
}



function is_super_admin( $user_id = false ) {

    if ( ! $user_id || $user_id == get_current_user_id() )
        $user = get_user_current();
    else
        $user = get_userdata( $user_id );
 
    if ( !have_posts( $user ) ) return false;

    if ( user_has_cap( $user->id, 'delete_users' ) ) return true;
 
    return false;
}


function map_meta_cap( $cap, $user_id ) {

    $args = array_slice( func_get_args(), 2 );

    $caps = array();
 
    switch ( $cap ) {

    case 'remove_user':
        // In multisite the user must be a super admin to remove themselves.
        if ( isset( $args[0] ) && $user_id == $args[0] && ! is_super_admin( $user_id ) ) {
            $caps[] = 'do_not_allow';
        } else {
            $caps[] = 'remove_users';
        }
        break;
    case 'promote_user':
    case 'add_users':
        $caps[] = 'promote_users';
        break;
    case 'edit_user':
    case 'edit_users':
        // Allow user to edit itself
        if ( 'edit_user' == $cap && isset( $args[0] ) && $user_id == $args[0] ) break;
            $caps[] = 'edit_users'; // edit_user maps to edit_users.
        break;
    case 'delete_post':
    case 'delete_page':
        $caps[] = 'do_not_allow';
        break;
    case 'edit_post':
    case 'edit_page':
        $caps[] = 'do_not_allow';
        break;
    case 'read_post':
    case 'read_page':
        $caps[] = 'do_not_allow';
        break;
    case 'publish_post':
        $caps[] = 'do_not_allow';
        break;
    case 'edit_post_meta':
    case 'delete_post_meta':
    case 'add_post_meta':
    case 'edit_comment_meta':
    case 'delete_comment_meta':
    case 'add_comment_meta':
    case 'edit_term_meta':
    case 'delete_term_meta':
    case 'add_term_meta':
    case 'edit_user_meta':
    case 'delete_user_meta':
    case 'add_user_meta':
        list( $_, $object_type, $_ ) = explode( '_', $cap );
        $object_id = (int) $args[0];
 
        switch ( $object_type ) {
            case 'post':
                $post = get_post( $object_id );
                if ( ! $post ) {
                    break;
                }
 
                $sub_type = get_post_type( $post );
                break;
 
            case 'comment':
                $comment = get_comment( $object_id );
                if ( ! $comment ) {
                    break;
                }
 
                $sub_type = empty( $comment->comment_type ) ? 'comment' : $comment->comment_type;
                break;
 
            case 'term':
                $term = get_term( $object_id );
                if ( ! $term instanceof WP_Term ) {
                    break;
                }
 
                $sub_type = $term->taxonomy;
                break;
 
            case 'user':
                $user = get_user_by( 'id', $object_id );
                if ( ! $user ) {
                    break;
                }
 
                $sub_type = 'user';
                break;
        }
 
        if ( empty( $sub_type ) ) {
            $caps[] = 'do_not_allow';
            break;
        }
 
        $caps = map_meta_cap( "edit_{$object_type}", $user_id, $object_id );
 
        $meta_key = isset( $args[1] ) ? $args[1] : false;
 
        $has_filter = has_filter( "auth_{$object_type}_meta_{$meta_key}" ) || has_filter( "auth_{$object_type}_{$sub_type}_meta_{$meta_key}" );

        if ( $meta_key && $has_filter ) {
 
            $allowed = apply_filters( "auth_{$object_type}_meta_{$meta_key}", false, $meta_key, $object_id, $user_id, $cap, $caps );
 

            $allowed = apply_filters( "auth_{$object_type}_{$sub_type}_meta_{$meta_key}", $allowed, $meta_key, $object_id, $user_id, $cap, $caps );
 
            if ( ! $allowed ) {
                $caps[] = $cap;
            }
        } elseif ( $meta_key && is_protected_meta( $meta_key, $object_type ) ) {
            $caps[] = $cap;
        }
        break;
    case 'edit_comment':
        $caps[] = 'do_not_allow';
        break;
    case 'unfiltered_upload':
        $caps[] = 'do_not_allow';
        break;
    case 'edit_css' :
    case 'unfiltered_html' :
        $caps[] = 'unfiltered_html';
        break;
    case 'edit_files':
    case 'edit_plugins':
        $caps[] = 'edit_plugins';
        break;
    case 'edit_themes':
        $caps[] = 'edit_themes';
        break;
    case 'update_plugins':
    case 'delete_plugins':
    case 'install_plugins':
    case 'upload_plugins':
    case 'update_themes':
    case 'delete_themes':
    case 'install_themes':
    case 'upload_themes':
    case 'update_core':
        $caps[] = 'update_core';
        break;
    case 'install_languages':
    case 'update_languages':
        $caps[] = 'do_not_allow';
        break;
    case 'activate_plugins':
    case 'deactivate_plugins':
    case 'activate_plugin':
    case 'deactivate_plugin':
        $caps[] = 'activate_plugins';
        break;
    case 'delete_user':
    case 'delete_users':
        $caps[] = 'delete_users';
        break;
    case 'create_users':
        $caps[] = 'create_users';
        break;
    case 'delete_site':
        $caps[] = 'do_not_allow';
        break;
    case 'edit_term':
    case 'delete_term':
    case 'assign_term':
        $caps[] = 'do_not_allow';
        break;
    case 'edit_categories':
    case 'delete_categories':
        $caps[] = 'delete_categories';
        break;
    default:
        $caps[] = $cap;
    }
    return apply_filters( 'map_meta_cap', $caps, $cap, $user_id, $args );
}


function current_user_can( $capability ) {

    $current_user = get_user_current();
 
    if ( empty( $current_user ) ) return false;
 
    $args = array_slice( func_get_args(), 1 );

    $args = array_merge( array( $current_user->id, $capability ), $args );
 
    return call_user_func_array( 'user_has_cap', $args );
}


function add_role( $role, $display_name, $capabilities = array() ) {

    if ( empty( $role ) ) return;

    return skd_roles()->add_role( $role, $display_name, $capabilities );
}

function remove_role( $role ) {

    skd_roles()->remove_role( $role );

}

function get_role( $role ) {
    return skd_roles()->get_role( $role );
}

function add_cap( $role, $cap, $grant = true ) {
    return skd_roles()->add_cap( $role, $cap, $grant );
}

function remove_cap( $role, $cap ) {
    return skd_roles()->remove_cap( $role, $cap );
}