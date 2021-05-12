<?php
if (!defined('ABSPATH')) exit;

$is_curator = false;

$user_meta = get_userdata($user->ID);
$user_roles = $user_meta->roles;
foreach ($user_roles as $role) {
    if (strpos($role, 'wppm') !== false) {
        $post_id = explode('_', $role);
        $post = get_post($post_id[1]);
        if (strtolower($post->post_title) === 'curator') {
            $is_curator = true;
        }
    }
}

if ($is_curator) {
    $label_name = get_user_meta($user->ID, 'pma_label_name', true);
    $pma_zip = get_user_meta($user->ID, 'pma_zip', true);
    $pma_city = get_user_meta($user->ID, 'pma_city', true);
    $pma_street_address = get_user_meta($user->ID, 'pma_street_address', true);
    $pma_phone1 = get_user_meta($user->ID, 'pma_phone1', true);
    $pma_phone2 = get_user_meta($user->ID, 'pma_phone2', true);
    $pma_label_logo = get_user_meta($user->ID, 'pma_label_logo', true);
}
$countryString = file_get_contents(PMA_BASE_PATH . "country.json");
$countries = json_decode($countryString);
$pma_social = get_user_meta($user->ID, 'pma_social', true);
$pma_dj_type = get_user_meta($user->ID, 'pma_dj_type', true);
$pma_dj_yoe = get_user_meta($user->ID, 'pma_dj_yoe', true);
$pma_dj_name = get_user_meta($user->ID, 'pma_dj_name', true);
$pma_country = get_user_meta($user->ID, 'pma_country', true);
$pma_state = get_user_meta($user->ID, 'pma_state', true);
$pma_found_via = get_user_meta($user->ID, 'pma_found_via', true);
$pma_bio = get_user_meta($user->ID, 'pma_bio', true);
if (!is_array($pma_dj_type)) $pma_dj_type = [];
if (!is_array($pma_social)) $pma_social = [];

?>
<br>
<div class="w3eden" style="width: 800px;max-width: 100%">
    <div class="panel panel-default card card-default">
        <div class="panel-heading card-header">
            Social Settings
        </div>
        <div class="panel-body card-body">
            <div class="row">

                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper">
                        <label style="color:black!important" for="pma_website">Website
                            <?php
                            if (isset($pma_social['website']) && $pma_social['website'] !== '') {
                                echo "<a target='_blank' href='" . $pma_social['website'] . "'><i class='fas fa-external-link-alt'></i></a>";
                            }
                            ?>
                        </label>
                        <input type="url" name="pma_social[website]" id="pma_website" placeholder="Website URL"
                               value="<?= esc_attr(isset($pma_social['website']) ? $pma_social['website'] : '') ?>"
                               class="form-control pma_social">
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper password-input-wrapper">
                        <label style="color:black!important" for="pma_facebook">Facebook
                            <?php
                            if (isset($pma_social['facebook']) && $pma_social['facebook'] !== '') {
                                echo "<a target='_blank' href='" . $pma_social['facebook'] . "'><i class='fas fa-external-link-alt'></i></a>";
                            }
                            ?>
                        </label>
                        <input type="url" name="pma_social[facebook]" id="pma_facebook"
                               value="<?= esc_attr(isset($pma_social['facebook']) ? $pma_social['facebook'] : '') ?>"
                               placeholder="Facebook URL"
                               class="form-control pma_social">
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper">
                        <label style="color:black!important" for="pma_sc">SoundCloud
                            <?php
                            if (isset($pma_social['sc']) && $pma_social['sc'] !== '') {
                                echo "<a target='_blank' href='" . $pma_social['sc'] . "'><i class='fas fa-external-link-alt'></i></a>";
                            }
                            ?>
                        </label>
                        <input type="url" name="pma_social[sc]" id="pma_sc" placeholder="SoundCloud URL"
                               value="<?= esc_attr(isset($pma_social['sc']) ? $pma_social['sc'] : '') ?>"
                               class="form-control pma_social">
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper password-input-wrapper">
                        <label style="color:black!important" for="pma_twitter">Twitter
                            <?php
                            if (isset($pma_social['twitter']) && $pma_social['twitter'] !== '') {
                                echo "<a target='_blank' href='" . $pma_social['twitter'] . "'><i class='fas fa-external-link-alt'></i></a>";
                            }
                            ?>
                        </label>
                        <input type="url" name="pma_social[twitter]" id="pma_twitter" placeholder="Twitter URL"
                               value="<?= esc_attr(isset($pma_social['twitter']) ? $pma_social['twitter'] : '') ?>"
                               class="form-control pma_social">
                    </div>
                </div>
            </div>
            <div class="col-md-12 form-group pma-addon-field" style="padding: 0;">
                <div class="input-wrapper">
                    <label style="color:black!important" for="pma_insta">Instagram
                        <?php
                        if (isset($pma_social['insta']) && $pma_social['insta'] !== '') {
                            echo "<a target='_blank' href='" . $pma_social['insta'] . "'><i class='fas fa-external-link-alt'></i></a>";
                        }
                        ?>
                    </label>
                    <input placeholder="Instagram URL" type="url" id="pma_insta" name="pma_social[insta]"
                           value="<?= esc_attr(isset($pma_social['insta']) ? $pma_social['insta'] : '') ?>"
                           class="form-control pma_social">
                </div>
            </div>
            <br><br>
        </div>
    </div>
    <br>
    <div class="panel panel-default card card-default">
        <div class="panel-heading card-header">
            <?= $is_curator ? 'Label Info' : 'DJ Info' ?>
        </div>
        <div class="panel-body card-body">
            <?php if ($is_curator): ?>

                <div class="input-wrapper">
                    <label style="color:black!important" for="">Logo</label>
                    <div class="card-body">
                        <img style="max-height: 100px;max-width: 100px;" id="frontend-image"
                             src="<?= esc_attr($pma_label_logo) ?>"/>
                        <input name="pma_label_logo" id="pma_label_logo" hidden/>
                        <input id="frontend-button" type="button" value="Change Logo"
                               class="button btn btn-primary pull-right"
                               style="position: relative; z-index: 1;">
                    </div>


                </div>
                <br>

                <div class="col-md-12 form-group" style="padding: 0;">
                    <div class="input-wrapper">
                        <label for="pma_label_name" style="color:black!important">Label Name</label>
                        <input placeholder="Label Name" type="text" id="pma_label_name" name="pma_label_name"
                               value="<?= isset($label_name) ? $label_name : '' ?>" class="form-control">
                    </div>
                </div>
            <?php
            endif;
            ?>
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper">
                        <label for="pma_country" style="color:black!important">Country</label>
                        <select style="color:black!important" name="pma_country" value="<?= esc_attr($pma_country) ?>"
                                id="pma_country" required
                                class="form-control input-bg-color">

                            <?php
                            foreach ($countries as $country) {
                                if ($pma_country === $country->country)
                                    echo '<option selected value="' . $country->country . '">' . $country->country . '</option>';
                                else
                                    echo '<option value="' . $country->country . '">' . $country->country . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="input-wrapper">
                        <label for="pma_state" style="color:black!important">State / Province</label>
                        <input placeholder="State / Province" type="text" required id="pma_state" name="pma_state"
                               value="<?= esc_attr($pma_state) ?>" class="form-control">
                    </div>
                </div>
            </div>
            <?php
            if (!$is_curator):
                ?>
                <div class="row">
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label for="pma_dj_name" style="color:black!important">DJ Name</label>
                            <input placeholder="DJ Name" type="text" id="pma_dj_name" name="pma_dj_name"
                                   value="<?= esc_attr($pma_dj_name) ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label for="pma_dj_yoe" style="color:black!important">Years of Experience as a DJ?</label>
                            <input placeholder="Years of Experience as a DJ?" type="number" required id="pma_dj_yoe"
                                   value="<?= esc_attr($pma_dj_yoe) ?>" name="pma_dj_yoe" class="form-control">
                        </div>
                    </div>
                </div>
            <?php endif;
            if ($is_curator):
                ?>
                <div class="row">
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label style="color:black!important" for="pma_city" class="pma_input_label">City</label>
                            <input
                                    value="<?= isset($pma_city) ? $pma_city : '' ?>"
                                    placeholder="City" type="text" id="pma_city"
                                    name="pma_city" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label style="color:black!important" for="pma_zip" class="pma_input_label">Postal Code / Zip
                                code</label>
                            <input placeholder="Postal Code / Zip code" type="text" required id="pma_zip"
                                   value="<?= isset($pma_zip) ? $pma_zip : '' ?>"
                                   name="pma_zip" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12" style="padding: 0;">
                    <div class="input-wrapper">
                        <label style="color:black!important" for="pma_street_address" class="pma_input_label">Street
                            address</label>
                        <textarea placeholder="Street address" required id="pma_street_address"
                                  name="pma_street_address"
                                  class="form-control"><?= isset($pma_street_address) ? $pma_street_address : '' ?></textarea>
                    </div>
                </div>

                <div class="row ">
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label style="color:black!important" for="pma_phone1" class="pma_input_label">Primary /
                                Mobile Phone</label>
                            <input placeholder="Primary / Mobile Phone" required type="text" id="pma_phone1"
                                   value="<?= isset($pma_phone1) ? $pma_phone1 : '' ?>"
                                   name="pma_phone1" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="input-wrapper">
                            <label style="color:black!important" for="pma_phone2" class="pma_input_label">Home /
                                Secondary Phone</label>
                            <input placeholder="Home / Secondary Phone" type="text" id="pma_phone2"
                                   value="<?= isset($pma_phone2) ? $pma_phone2 : '' ?>"
                                   name="pma_phone2" class="form-control">
                        </div>
                    </div>
                </div>

            <?php endif; ?>
            <div class="col-md-12 form-group pma-addon-field" style="padding: 0;">
                <div class="input-wrapper">
                    <label style="color:black!important" for="pma_found_via">How did you find out about
                        RiddimStream?</label>
                    <select style="color:black!important" value="<?= esc_attr($pma_found_via) ?>" name="pma_found_via"
                            id="pma_found_via"
                            class="form-control" required>
                        <option value="">None</option>
                        <option <?php echo($pma_found_via === 'Direct Email Marketing From' ? 'selected' : '') ?>
                                value="Direct Email Marketing From">
                            Direct Email Marketing From
                        </option>
                        <option <?php echo($pma_found_via === 'Internet Keyword Search' ? 'selected' : '') ?>
                                value="Internet Keyword Search">Internet Keyword Search
                        </option>
                        <option <?php echo($pma_found_via === 'Instagram Advertisement' ? 'selected' : '') ?>
                                value="Instagram Advertisement">Instagram Advertisement
                        </option>
                        <option <?php echo($pma_found_via === 'Facebook Advertisement' ? 'selected' : '') ?>
                                value="Facebook Advertisement">Facebook Advertisement
                        </option>
                        <option <?php echo($pma_found_via === 'Google Advertisement' ? 'selected' : '') ?>
                                value="Google Advertisement">Google Advertisement
                        </option>
                        <option <?php echo($pma_found_via === 'Word Of Mouth' ? 'selected' : '') ?>
                                value="Word Of Mouth">Word Of Mouth
                        </option>
                        <option <?php echo($pma_found_via === 'Friend Recommendation' ? 'selected' : '') ?>
                                value="Friend Recommendation">Friend Recommendation
                        </option>
                        <option <?php echo($pma_found_via === 'Other' ? 'selected' : '') ?>
                                value="Other">Other
                        </option>
                    </select>
                </div>
            </div>
            <?php
            if (!$is_curator):
                ?>
                <div class="col-md-12 form-group pma-addon-field" style="padding: 0;">
                    <div class="input-wrapper full-opacity" style="padding: 10px 20px;">
                        <label style="color:black!important" for="pma_dj_type" style="font-weight: normal;">What type of
                            DJ are You? (More
                            than
                            one
                            selection
                            possible)
                        </label>
                        <div class="row checkboxes">
                            <div class="col-md-12 col-sm-6 col-xs-6" style="padding: 0;">
                                <div class="row">
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_club">
                                        <input data-dj-type="club" type="checkbox" name="pma_dj_type[]"
                                               value="club"
                                            <?= esc_attr(array_search('club', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               id="pma_dj_type_club"
                                               class="input-bg-color">
                                        <span>Club</span>
                                    </label>
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_mobile">
                                        <input data-dj-type="mobile" type="checkbox" name="pma_dj_type[]"
                                            <?= esc_attr(array_search('mobile', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               value="mobile"
                                               id="pma_dj_type_mobile">
                                        <span>Mobile</span>
                                    </label>
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_radio">
                                        <input data-dj-type="radio" type="checkbox" name="pma_dj_type[]"
                                            <?= esc_attr(array_search('radio', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               value="radio"
                                               id="pma_dj_type_radio">
                                        <span>Radio</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-6" style="padding: 0;">
                                <div class="row mt-1">
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_mixshow">
                                        <input data-dj-type="mixshow" type="checkbox" name="pma_dj_type[]"
                                            <?= esc_attr(array_search('mixshow', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               value="mixshow"
                                               id="pma_dj_type_mixshow">
                                        <span>Mixshow</span>
                                    </label>
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_satellite">
                                        <input data-dj-type="satellite" type="checkbox" name="pma_dj_type[]"
                                            <?= esc_attr(array_search('satellite', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               value="satellite"
                                               id="pma_dj_type_satellite">
                                        <span>Satellite</span>
                                    </label>
                                    <label style="color:black!important" class="col-sm-4" for="pma_dj_type_internet">
                                        <input data-dj-type="internet" type="checkbox" name="pma_dj_type[]"
                                            <?= esc_attr(array_search('internet', $pma_dj_type, true) > -1 ? 'checked' : '') ?>
                                               value="internet"
                                               id="pma_dj_type_internet">
                                        <span>Internet</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="form-group col-md-12" style="padding: 0;">
                <div class="input-wrapper">
                    <label style="color:black!important" for="pma_bio" class="pma_input_label">Bio/Additional supporting
                        information<span
                                style="color:red;">*</span></label>
                    <textarea placeholder="Bio/Additional supporting information" required id="pma_bio"
                              name="pma_bio"
                              class="form-control"><?= isset($pma_bio) ? $pma_bio : '' ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<br>