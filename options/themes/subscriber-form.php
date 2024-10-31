<div id="newsletter" class="container-fluid space newsletter">
    <div class="container">
        <h2 data-sr="enter top"><?php echo esc_html($wl_rcsm_options['subscriber_form_title']);?></h2>
        <h4 data-sr="enter top"><?php echo esc_html($wl_rcsm_options['subscriber_form_sub_title']);?></h4>
        <?php if ($wl_rcsm_options['subscriber_form_icon'] == null) { ?>
        <span> <span class="<?php echo esc_attr($wl_rcsm_options['subscriber_form_icon']);?>  icon"></span> </span>
        <?php } else { ?>
        <span>..........&nbsp;<span class="<?php echo esc_attr($wl_rcsm_options['subscriber_form_icon']);?> icon"></span>&nbsp;..........</span>
        <?php } ?>
        <p class="desc"><?php echo esc_attr($wl_rcsm_options['subscriber_form_message']);?></p>
        <!-- subscriber form for wp mail -->
        <?php if($wl_rcsm_options['subscribe_select']!='smtp_mail') { ?>
            <script>
                function validateForm1() {
                    var x = document.forms["subscriber-form"]["subscribe_email"].value;
                    var atpos = x.indexOf("@");
                    var dotpos = x.lastIndexOf(".");
                    var error_msg = ".sub_error_msg";
                    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
                        jQuery(error_msg).show();
                        return false;
                    }
                }
            </script>
            <form method="post" action="" onsubmit="return validateForm1()" class="subscriber-form" name="subscriber-form">
                <div class="form-group" data-sr="enter bottom over 1s and move 110px wait 0.5s">
                    <div id="error_email2" class="validation_error error_email"></div>
                    <input type="text" name="f_name" id="f_sub_name"  class="form-control subscribe-input-layout" placeholder="<?php echo esc_attr($wl_rcsm_options['sub_form_button_f_name'] ?? 'First Name'); ?>" required='required'>
                    <input type="text" name="l_name" id="l_sub_name"  class="form-control subscribe-input-layout" placeholder="<?php echo esc_attr($wl_rcsm_options['sub_form_button_l_name'] ?? 'Last Name'); ?>" required='required'>
                    <input type="email" name="subscribe_email" id="edmm-sub-email1"  class="form-control subscribe-input-layout2" placeholder="<?php echo esc_attr($wl_rcsm_options['sub_form_subscribe_title'] ?? 'Email'); ?>" required='required'>
                    <span class="sub_error_msg" style="display:none; color:red;"><?php esc_html_e("* Invalid email address.","weblizar"); ?></span>
                    <?php wp_nonce_field('subscriber-nonce', 'subscriber_nonce_field'); ?>
                    <button name="submit_subscriber" class="subscriber_submit btn" type="submit">
                    <?php echo esc_html($wl_rcsm_options['sub_form_button_text'] ?? 'Subscribe'); ?></button>
                    <div class="subscribe-message">
                    <?php
                    if (isset($_SESSION['mail_sent'])) {
                        echo esc_html($_SESSION['mail_sent']);
                        unset($_SESSION['mail_sent']);
                    }
                    if (isset($_SESSION['mail_sent_msg'])) {
                        echo esc_html($_SESSION['mail_sent_msg']);
                        unset($_SESSION['mail_sent_msg']);
                    }

                    if (isset($_SESSION['subscribe_msg'])) {
                        echo esc_html($_SESSION['subscribe_msg']);
                        unset($_SESSION['subscribe_msg']);
                    }

                    if (isset($_GET['act_code']) && isset($_GET['email'])) {
                        $act_code = sanitize_text_field($_GET['act_code']);

						// verify nonce
						if ( ! wp_verify_nonce( $_GET['subscriber_nonce_field'], 'subscriber-nonce' ) ) {
							wp_die( 'Security check' );
						}
                        $email = sanitize_email($_GET['email']);
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'rcsm_subscribers';
                        $user_search_result = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$table_name` WHERE `email` = %s AND `act_code` = %s", $email, $act_code));
                        if (!empty($user_search_result)) {
                            if ($user_search_result->flag == 1) {
                                echo "<h4 class='alert alert-info'>" . esc_html($wl_rcsm_options['sub_form_subscribe_already_confirm_message']) . "</h4>";
                            } else {
                                if ($wpdb->query($wpdb->prepare("UPDATE `$table_name` SET `flag` = '1' WHERE `email` = %s", $email))) {
                                    echo "<h4 class='alert alert-info'>" . esc_html($wl_rcsm_options['sub_form_subscribe_confirm_success_message']) . "</h4>";
                                }
                            }
                        } else {
                            echo "<h4 class='alert alert-info'>" . esc_html($wl_rcsm_options['sub_form_invalid_confirmation_message']) . "</h4>";
                        }
                    }
                     ?>
                    </div>
                </div>
            </form>
        <?php } ?>
        <p class="subscribe-message"></p>
    </div>
</div>
