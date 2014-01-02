<?php

       add_filter('adu_group_actions', 'adn_group_email_users', 20, 2);
       add_filter('adu_group_actions', 'adn_group_sms_users', 30, 2);
       add_action('adu_group_javascript_header','adu_group_javascript_header',30);
       
       function adn_group_email_users($html, $group_id){
       
               $html.="<div class='adu_group_action'><a class='button NewNewsletter' href='#' id='SendNewsletter_$group_id'>".__('Newsletter Email').'</a></div>';
       
               return $html;
       
       }
       
       function adn_group_sms_users($html, $group_id){
       
               $html.="<div class='adu_group_action'><a class='button' href='/wp-admin/users.php?page=adn_newsletters&group_id=$group_id'>".__('Newsletter SMS').'</a></div>';
       
               return $html;
       
       }


function adu_group_javascript_header(){
    $Q = array("post_type"=>"bew_nw","orderby"=>"date","order"=>"DESC");
    $R = new WP_Query($Q);
    foreach($R->posts as $k=>$v){
              $selectStr.= "<option value=\"".$v->ID."\">".$v->post_title."</option>";
         }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".NewNewsletter").click(function(){
            GroupName = jQuery(this).attr("id").substr(15,50);
            jQuery(this).parent().after().html('\n\
                <form action="" method="POST">\n\
                    <input type="hidden" value="'+GroupName+'" name="groupName" />\n\
                    <input type="hidden" value="bew_register_newsletter" name="action" />\n\
                    Newsletter template\n\
                    <select name="template">\n\
                        <?= $selectStr?>
                    </select>\n\
                    <input type="submit" value="Valider" class="button">\n\
                </form>\n\
            ');
        });
    });
    </script>
<?php } ?>