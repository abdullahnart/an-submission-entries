<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Abdullah Naseem Submission Manager for Contact Form 7
 * Plugin URI:        https://github.com/abdullahnart/an-submission-entries
 * Description:       Store and manage Contact Form 7 submissions from your WordPress dashboard.
 * Version:           1.1.2
 * Author:            Abdullah Naseem
 * Author URI:        https://github.com/abdullahnart/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       an-submission-entries
 * Requires Plugins: contact-form-7
 * Requires at least: 5.7
 * Requires PHP:      7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * =========================================================
 * DATABASE
 * =========================================================
 */

/**
 * Create / update submissions table.
 */
function an_create_cf7_custom_table() {

    global $wpdb;

    $table_name = $wpdb->prefix . 'cf7_submissions';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        form_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        form_title VARCHAR(255) NOT NULL,
        submitted_data LONGTEXT NOT NULL,
        submit_time DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY form_id (form_id),
        KEY submit_time (submit_time)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta( $sql );
}


/**
 * Run database setup when plugin is activated.
 */
function an_activate_submission_entries() {

    an_create_cf7_custom_table();

    update_option(
        'an_submission_entries_db_version',
        '2.0.0'
    );
}

register_activation_hook(
    __FILE__,
    'an_activate_submission_entries'
);


/**
 * =========================================================
 * SAVE CF7 SUBMISSIONS
 * =========================================================
 */

add_action(
    'wpcf7_before_send_mail',
    'an_save_cf7_submission_to_db'
);


/**
 * Save Contact Form 7 submission.
 */
function an_save_cf7_submission_to_db( $contact_form ) {

    global $wpdb;

    // Make sure CF7 submission class exists.
    if ( ! class_exists( 'WPCF7_Submission' ) ) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();

    if ( ! $submission ) {
        return;
    }

    $table_name = $wpdb->prefix . 'cf7_submissions';

    /*
     * Get form information.
     */
    $form_id    = absint( $contact_form->id() );
    $form_title = $contact_form->title();

    /*
     * Get submitted data.
     */
    $data = $submission->get_posted_data();

    /*
     * Remove internal Contact Form 7 fields.
     */
    $remove_fields = array(
        '_wpcf7',
        '_wpcf7_version',
        '_wpcf7_locale',
        '_wpcf7_unit_tag',
        '_wpcf7_container_post',
        '_wpcf7_posted_data_hash',
    );

    foreach ( $remove_fields as $field ) {
        unset( $data[ $field ] );
    }

    /*
     * Save submission.
     */
    $wpdb->insert(
        $table_name,
        array(
            'form_id'        => $form_id,
            'form_title'     => $form_title,
            'submitted_data' => maybe_serialize( $data ),
            'submit_time'    => current_time( 'mysql' ),
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s',
        )
    );
}


/**
 * =========================================================
 * ADMIN MENU
 * =========================================================
 */

add_action(
    'admin_menu',
    'an_register_submission_menu'
);


/**
 * Add WordPress admin menu.
 */
function an_register_submission_menu() {

    add_submenu_page(
        'wpcf7',
        'Submission Manager',
        'Submission Entries',
        'manage_options',
        'an-submission-entries',
        'an_render_forms_page'
    );
}


/**
 * =========================================================
 * ADMIN STYLES
 * =========================================================
 */

add_action(
    'admin_enqueue_scripts',
    'an_submission_entries_admin_styles'
);


/**
 * Load plugin admin CSS only on our page.
 */
function an_submission_entries_admin_styles( $hook ) {

    // Load styles only on the Submission Entries submenu.
    if ( false === strpos( $hook, 'an-submission-entries' ) ) {
        return;
    }

    wp_register_style(
        'an-submission-entries-admin',
        false,
        array(),
        '2.0.0'
    );

    wp_enqueue_style(
        'an-submission-entries-admin'
    );

    $css = '
        .an-se-wrap {
            max-width: 1200px;
            margin: 25px 20px 40px 0;
        }

        .an-se-header {
            background: linear-gradient(135deg, #111827, #1f2937);
            color: #fff;
            padding: 28px 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        .an-se-header h1 {
            color: #fff;
            font-size: 25px;
            margin: 0 0 8px;
            font-weight: 700;
        }

        .an-se-header p {
            color: #d1d5db;
            margin: 0;
            font-size: 14px;
        }

        .an-se-brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: #fff;
            color: #111827;
            border-radius: 9px;
            font-weight: 800;
            margin-right: 12px;
            vertical-align: middle;
        }

        .an-se-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,.04);
        }

        .an-se-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .an-se-card-header h2 {
            margin: 0;
            font-size: 17px;
            color: #111827;
        }

        .an-se-table {
            width: 100%;
            border-collapse: collapse;
        }

        .an-se-table th {
            text-align: left;
            padding: 14px 18px;
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e5e7eb;
        }

        .an-se-table td {
            padding: 17px 18px;
            border-bottom: 1px solid #f0f1f3;
            vertical-align: middle;
        }

        .an-se-table tr:last-child td {
            border-bottom: 0;
        }

        .an-se-table tbody tr:hover {
            background: #fafafa;
        }

        .an-se-form-name {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .an-se-form-id {
            color: #9ca3af;
            font-size: 12px;
            margin-top: 3px;
        }

        .an-se-count {
            display: inline-block;
            min-width: 34px;
            padding: 5px 9px;
            text-align: center;
            border-radius: 20px;
            background: #eef2ff;
            color: #4338ca;
            font-weight: 600;
        }

        .an-se-button {
            display: inline-block;
            background: #111827;
            color: #fff !important;
            padding: 8px 14px;
            border-radius: 7px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }

        .an-se-button:hover {
            background: #374151;
        }

        .an-se-back {
            display: inline-block;
            margin-bottom: 16px;
            text-decoration: none;
            color: #374151;
            font-weight: 600;
        }

        .an-se-entry {
            padding: 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .an-se-entry:last-child {
            border-bottom: 0;
        }

        .an-se-entry-meta {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .an-se-data {
            width: 100%;
            border-collapse: collapse;
        }

        .an-se-data td {
            padding: 9px 10px;
            border-bottom: 1px solid #f0f1f3;
            vertical-align: top;
        }

        .an-se-data td:first-child {
            width: 25%;
            font-weight: 600;
            color: #374151;
        }

        .an-se-empty {
            padding: 50px 25px;
            text-align: center;
            color: #6b7280;
        }

        .an-se-empty-icon {
            font-size: 35px;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        @media screen and (max-width: 782px) {
            .an-se-wrap {
                margin-right: 12px;
            }

            .an-se-table {
                display: block;
                overflow-x: auto;
            }
        }
    ';

    wp_add_inline_style(
        'an-submission-entries-admin',
        $css
    );
}


/**
 * =========================================================
 * FORMS PAGE
 * =========================================================
 */

/**
 * Display all Contact Form 7 forms.
 */
function an_render_forms_page() {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    /*
     * Make sure Contact Form 7 is active.
     */
    if ( ! class_exists( 'WPCF7_ContactForm' ) ) {

        echo '<div class="wrap">';
        echo '<div class="notice notice-warning">';
        echo '<p><strong>AN Submission Entries:</strong> Contact Form 7 is not active.</p>';
        echo '</div>';
        echo '</div>';

        return;
    }

    /*
     * If a form was selected, show entries.
     */
    if ( isset( $_GET['entry_id'] ) && isset( $_GET['form_id'] ) ) {

        an_render_submission_entry(
            absint( $_GET['form_id'] ),
            absint( $_GET['entry_id'] )
        );

        return;
    }

    if ( isset( $_GET['form_id'] ) ) {

        $form_id = absint(
            $_GET['form_id']
        );

        an_render_form_entries(
            $form_id
        );

        return;
    }

    /*
     * Get Contact Form 7 forms.
     */
    $forms = WPCF7_ContactForm::find(
        array(
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        )
    );

    global $wpdb;

    $table_name = $wpdb->prefix . 'cf7_submissions';

    ?>

    <div class="wrap an-se-wrap">

        <div class="an-se-header">

            <h1>
                <span class="an-se-brand">AN</span>
                Submission Entries
            </h1>

            <p>
                Manage your Contact Form 7 submissions from one place.
            </p>

        </div>


        <div class="an-se-card">

            <div class="an-se-card-header">

                <h2>
                    Contact Form 7 Forms
                </h2>

            </div>


            <?php if ( empty( $forms ) ) : ?>

                <div class="an-se-empty">

                    <div class="an-se-empty-icon">
                        <span class="dashicons dashicons-feedback"></span>
                    </div>

                    <p>
                        No Contact Form 7 forms found.
                    </p>

                </div>

            <?php else : ?>

                <table class="an-se-table">

                    <thead>

                        <tr>

                            <th>
                                Form
                            </th>

                            <th>
                                Entries
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ( $forms as $form ) : ?>

                            <?php

                            $form_id = absint(
                                $form->id()
                            );

                            $entry_count = (int) $wpdb->get_var(
                                $wpdb->prepare(
                                    "SELECT COUNT(*) FROM $table_name WHERE form_id = %d",
                                    $form_id
                                )
                            );

                            $entries_url = add_query_arg(
                                array(
                                    'page'    => 'an-submission-entries',
                                    'form_id' => $form_id,
                                ),
                                admin_url( 'admin.php' )
                            );

                            ?>

                            <tr>

                                <td>

                                    <div class="an-se-form-name">
                                        <?php echo esc_html( $form->title() ); ?>
                                    </div>

                                    <div class="an-se-form-id">
                                        Form ID: <?php echo esc_html( $form_id ); ?>
                                    </div>

                                </td>


                                <td>

                                    <span class="an-se-count">
                                        <?php echo esc_html( $entry_count ); ?>
                                    </span>

                                </td>


                                <td>

                                    <a
                                        class="an-se-button"
                                        href="<?php echo esc_url( $entries_url ); ?>"
                                    >
                                        View Entries
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

    <?php
}


/**
 * =========================================================
 * FORM ENTRIES PAGE
 * =========================================================
 */

/**
 * Display a list of submissions for the selected form.
 */
function an_render_form_entries( $form_id ) {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
        return;
    }

    $form = WPCF7_ContactForm::get_instance(
        $form_id
    );

    if ( ! $form ) {

        echo '<div class="wrap an-se-wrap">';
        echo '<div class="notice notice-error"><p>Form not found.</p></div>';
        echo '</div>';

        return;
    }

    global $wpdb;

    $table_name = $wpdb->prefix . 'cf7_submissions';

    $entries = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, form_id, submitted_data, submit_time FROM $table_name
             WHERE form_id = %d
             ORDER BY submit_time DESC",
            $form_id
        )
    );

    $back_url = admin_url(
        'admin.php?page=an-submission-entries'
    );

    ?>

    <div class="wrap an-se-wrap">

        <a
            class="an-se-back"
            href="<?php echo esc_url( $back_url ); ?>"
        >
            ← Back to Forms
        </a>


        <div class="an-se-header">

            <h1>
                <span class="an-se-brand">AN</span>
                <?php echo esc_html( $form->title() ); ?>
            </h1>

            <p>
                Submission entries for this Contact Form 7 form.
            </p>

        </div>


        <div class="an-se-card">

            <div class="an-se-card-header">

                <h2>
                    <?php echo esc_html( count( $entries ) ); ?>
                    Entries
                </h2>

            </div>


            <?php if ( empty( $entries ) ) : ?>

                <div class="an-se-empty">

                    <div class="an-se-empty-icon">
                        <span class="dashicons dashicons-email-alt"></span>
                    </div>

                    <p>
                        No submissions found for this form yet.
                    </p>

                </div>

            <?php else : ?>


                <table class="an-se-table">
                    <thead>
                        <tr>
                            <th>Entry</th>
                            <th>Date submitted</th>
                            <th>Preview</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $entries as $entry ) : ?>
                            <?php
                            $entry_data = maybe_unserialize( $entry->submitted_data );
                            $preview    = '';
                            if ( is_array( $entry_data ) && ! empty( $entry_data ) ) {
                                $preview_value = reset( $entry_data );
                                $preview       = is_array( $preview_value ) ? implode( ', ', $preview_value ) : (string) $preview_value;
                            }
                            $entry_url = add_query_arg(
                                array(
                                    'page'     => 'an-submission-entries',
                                    'form_id'  => $form_id,
                                    'entry_id' => absint( $entry->id ),
                                ),
                                admin_url( 'admin.php' )
                            );
                            ?>
                            <tr>
                                <td>
                                    <a class="an-se-form-name" href="<?php echo esc_url( $entry_url ); ?>">
                                        Entry #<?php echo esc_html( $entry->id ); ?>
                                    </a>
                                </td>
                                <td><?php echo esc_html( $entry->submit_time ); ?></td>
                                <td><?php echo esc_html( wp_trim_words( $preview, 12 ) ); ?></td>
                                <td><a class="an-se-button" href="<?php echo esc_url( $entry_url ); ?>">View Entry</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            <?php endif; ?>

        </div>

    </div>

    <?php
}


/** Display one submission on its own page. */
function an_render_submission_entry( $form_id, $entry_id ) {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'cf7_submissions';
    $entry      = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d AND form_id = %d",
            $entry_id,
            $form_id
        )
    );

    if ( ! $entry ) {
        echo '<div class="wrap an-se-wrap"><div class="notice notice-error"><p>Entry not found.</p></div></div>';
        return;
    }

    $form     = class_exists( 'WPCF7_ContactForm' ) ? WPCF7_ContactForm::get_instance( $form_id ) : false;
    $back_url = add_query_arg(
        array( 'page' => 'an-submission-entries', 'form_id' => $form_id ),
        admin_url( 'admin.php' )
    );
    $data = maybe_unserialize( $entry->submitted_data );
    ?>
    <div class="wrap an-se-wrap">
        <a class="an-se-back" href="<?php echo esc_url( $back_url ); ?>">← Back to Entries</a>
        <div class="an-se-header">
            <h1><span class="an-se-brand">AN</span>Entry #<?php echo esc_html( $entry->id ); ?></h1>
            <p><?php echo esc_html( $form ? $form->title() : $entry->form_title ); ?> · Submitted <?php echo esc_html( $entry->submit_time ); ?></p>
        </div>
        <div class="an-se-card">
            <div class="an-se-card-header"><h2>Submission Details</h2></div>
            <div class="an-se-entry">
                <?php if ( is_array( $data ) && ! empty( $data ) ) : ?>
                    <table class="an-se-data"><tbody>
                        <?php foreach ( $data as $key => $value ) : ?>
                            <?php $value = is_array( $value ) ? implode( ', ', $value ) : (string) $value; ?>
                            <tr><td><?php echo esc_html( $key ); ?></td><td><?php echo nl2br( esc_html( $value ) ); ?></td></tr>
                        <?php endforeach; ?>
                    </tbody></table>
                <?php else : ?>
                    <p>No submission data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}


/**
 * =========================================================
 * FRONTEND SHORTCODE
 * =========================================================
 */

/**
 * Shortcode:
 *
 * [cf7_submissions]
 *
 * [cf7_submissions form="Contact Form" limit="20"]
 */
// function an_cf7_submissions_shortcode( $atts ) {

//     global $wpdb;

//     $table_name = $wpdb->prefix . 'cf7_submissions';

//     $atts = shortcode_atts(
//         array(
//             'form'  => '',
//             'limit' => 20,
//         ),
//         $atts,
//         'cf7_submissions'
//     );

//     $limit = absint(
//         $atts['limit']
//     );

//     if ( $limit < 1 ) {
//         $limit = 20;
//     }

//     $query = "SELECT * FROM $table_name";

//     $where = array();

//     if ( ! empty( $atts['form'] ) ) {

//         $where[] = $wpdb->prepare(
//             'form_title = %s',
//             $atts['form']
//         );
//     }

//     if ( ! empty( $where ) ) {

//         $query .= ' WHERE ' . implode(
//             ' AND ',
//             $where
//         );
//     }

//     $query .= $wpdb->prepare(
//         ' ORDER BY submit_time DESC LIMIT %d',
//         $limit
//     );

//     $results = $wpdb->get_results(
//         $query
//     );

//     if ( ! $results ) {
//         return '<p>No submissions found.</p>';
//     }

//     $output = '<div class="cf7-submissions">';

//     foreach ( $results as $row ) {

//         $data = maybe_unserialize(
//             $row->submitted_data
//         );

//         $output .= '<div class="cf7-entry">';
//         $output .= '<strong>Form:</strong> ' . esc_html( $row->form_title ) . '<br>';
//         $output .= '<strong>Time:</strong> ' . esc_html( $row->submit_time ) . '<br>';
//         $output .= '<strong>Data:</strong><br>';

//         if ( is_array( $data ) ) {

//             $output .= '<ul>';

//             foreach ( $data as $key => $value ) {

//                 if ( is_array( $value ) ) {

//                     $value = implode(
//                         ', ',
//                         $value
//                     );
//                 }

//                 $output .= '<li>';
//                 $output .= '<strong>' . esc_html( $key ) . ':</strong> ';
//                 $output .= esc_html( $value );
//                 $output .= '</li>';
//             }

//             $output .= '</ul>';
//         }

//         $output .= '</div>';
//     }

//     $output .= '</div>';

//     return $output;
// }

// add_shortcode(
//     'cf7_submissions',
//     'an_cf7_submissions_shortcode'
// );



/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_abdullah_naseem_submission_manager_cf7() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( 'b4e8ed6e-bb08-4bcf-81ea-a173f64b9a15', 'Abdullah Naseem Submission Manager for Contact Form 7', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_abdullah_naseem_submission_manager_cf7();
