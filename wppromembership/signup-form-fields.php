<?php

$countryString = file_get_contents(PMA_BASE_PATH . "country.json");
$countries = json_decode($countryString);
$plan = get_post($_REQUEST['plan_id']);

?>

<!-- Circles which indicates the steps of the form: -->
<div style="text-align:center;margin-bottom:20px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
</div>

<!-- One "tab" for each step in the form: -->

<div class="step-tab">
    <?php
    $fname_placehoder = strtolower($plan->post_title) !== 'curator' ? 'First Name' : 'Label Representative First Name';
    $lname_placehoder = strtolower($plan->post_title) !== 'curator' ? 'Last Name' : 'Label Representative Last Name';

    if (strtolower($plan->post_title) === 'curator'): ?>
        <div class="form-group">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_label_name" class="pma_input_label">Label Name<span style="color:red;">*</span></label>
                <input placeholder="Label Name" type="text" required id="pma_label_name"
                       name="pma_label_name"
                       class="form-control">
            </div>
        </div>
    <?php endif; ?>
    <div class="form-row">
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_fname" class="pma_input_label"><?= esc_attr($fname_placehoder) ?><span
                            style="color:red;">*</span></label>
                <input placeholder="<?= esc_attr($fname_placehoder) ?>" type="text" required id="pma_fname"
                       name="<?= esc_attr__(WPPM_SIGNUP_FORM_DATA_KEY . '[first_name]') ?>"
                       class="form-control">
            </div>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_lname" class="pma_input_label"><?= esc_attr($lname_placehoder) ?><span
                            style="color:red;">*</span></label>
                <input placeholder="<?= esc_attr($lname_placehoder) ?>" type="text" required id="pma_lname"
                       name="<?= esc_attr__(WPPM_SIGNUP_FORM_DATA_KEY . '[last_name]') ?>"
                       class="form-control">
            </div>
        </div>
    </div>
    <?php if (strtolower($plan->post_title) !== 'curator'): ?>
        <div class="form-group pma-addon-field">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_dj_name" class="pma_input_label">DJ Name<span style="color:red;">*</span></label>
                <input required placeholder="DJ Name" type="text" id="pma_dj_name" name="pma_dj_name"
                       class="form-control">
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group pma-addon-field">
        <div class="input-wrapper" style="background: #0a0a0a !important;">
            <label for="pma_email" class="pma_input_label">Email<span style="color:red;">*</span></label>
            <input placeholder="Email Address" type="email" required id="pma_email"
                   name="<?= esc_attr__(WPPM_SIGNUP_FORM_DATA_KEY . '[user_email]') ?>"
                   class="form-control">
        </div>
    </div>

    <div class="form-group pma-addon-field">
        <div class="input-wrapper" style="background: #0a0a0a !important;">
            <label for="pma_username" class="pma_input_label">Username<span style="color:red;">*</span></label>
            <input placeholder="User Login ID" type="text" required id="pma_username"
                   name="<?= esc_attr__(WPPM_SIGNUP_FORM_DATA_KEY . '[user_login]') ?>"
                   class="form-control">
        </div>
    </div>

    <div class="form-row" id="row_password">
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper password-input-wrapper input-focused" style="background: #0a0a0a !important;">
                <label for="pma_password" class="pma_input_label">Password<span style="color:red;">*</span></label>
                <input type="password" name="<?= esc_attr__(WPPM_SIGNUP_FORM_DATA_KEY . '[user_pass]') ?>"
                       id="pma_password" placeholder="Be Secure" required class="form-control"></div>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper password-input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_confirm_pass" class="pma_input_label">Confirm Password<span style="color:red;">*</span></label>
                <input type="password" name="confirm_user_pass" confirm="last" id="pma_confirm_pass"
                       placeholder="Do Not Forget" required class="form-control">
            </div>
        </div>
    </div>

</div>
<div class="step-tab">

    <?php if (strtolower($plan->post_title) === 'curator'): ?>
        <div class="form-group">
            <div class="input-wrapper p-0">
                <div class="file btn btn-dark w-100">
                    Upload Label Logo
                    <input accept="image/jpeg,image/pjpeg,image/jpeg,image/pjpeg,image/png,image/x-png,image/gif"
                           required id="pma_label_logo" type="file" name="pma_label_logo"/>
                </div>
            </div>
        </div>
        <p style="color: white">Valid file format : .jpg | .jpeg | .png | .gif</p>

    <?php endif; ?>
    <em style="color: white;">MUST PROVIDE AT LEAST ONE OF THE FOLLOWING. <br>
        WE NEED TO VERIFY YOUR STATUS AS A PROFESSIONAL DJ, PLEASE ENTER AT LEAST ONE PROFILE URL THAT
        WILL BEST SUPPORT YOUR APPLICATION AS A PROFESSIONAL DJ.
    </em><br><br>
    <div class="form-row">
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_website" class="pma_input_label">Website</label>
                <input type="url" name="pma_social[website]" id="pma_website" placeholder="Website URL"
                       class="form-control pma_social">
            </div>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper " style="background: #0a0a0a !important;">
                <label for="pma_facebook" class="pma_input_label">Facebook</label>
                <input type="url" name="pma_social[facebook]" id="pma_facebook" placeholder="Facebook URL"
                       class="form-control pma_social">
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_sc" class="pma_input_label">SoundCloud</label>
                <input type="url" name="pma_social[sc]" id="pma_sc" placeholder="SoundCloud URL"
                       class="form-control pma_social">
            </div>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_twitter" class="pma_input_label">Twitter</label>
                <input type="url" name="pma_social[twitter]" id="pma_twitter" placeholder="Twitter URL"
                       class="form-control pma_social">
            </div>
        </div>
    </div>
    <div class="form-group pma-addon-field">
        <div class="input-wrapper" style="background: #0a0a0a !important;">
            <label for="pma_insta" class="pma_input_label">Instagram</label>
            <input placeholder="Instagram URL" type="url" id="pma_insta" name="pma_social[insta]"
                   class="form-control pma_social">
        </div>
    </div>
</div>
<div class="step-tab">
    <div class="form-row">
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_country" class="pma_input_label">Country<span style="color:red;">*</span></label>
                <select name="pma_country" id="pma_country" required
                        class="form-control input-bg-color">

                    <?php
                    foreach ($countries as $country) {
                        echo '<option style="color:black!important;" value="' . $country->country . '">' . $country->country . '</option>';
                    } ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_state" class="pma_input_label">State / Province<span style="color:red;">*</span></label>
                <input placeholder="State / Province" type="text" required id="pma_state" name="pma_state"
                       class="form-control">
            </div>
        </div>
    </div>
    <?php if (strtolower($plan->post_title) === 'curator'): ?>

        <div class="form-row">
            <div class="form-group col-md-6 col-sm-12">
                <div class="input-wrapper" style="background: #0a0a0a !important;">
                    <label for="pma_city" class="pma_input_label">City</label>
                    <input placeholder="City" type="text" id="pma_city" name="pma_city" class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <div class="input-wrapper" style="background: #0a0a0a !important;">
                    <label for="pma_zip" class="pma_input_label">Postal Code / Zip code<span
                                style="color:red;">*</span></label>
                    <input placeholder="Postal Code / Zip code" type="text" required id="pma_zip"
                           name="pma_zip" class="form-control">
                </div>
            </div>
        </div>
        <div class="form-group ">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_street_address" class="pma_input_label">Street address<span
                            style="color:red;">*</span></label>
                <textarea placeholder="Street address" required id="pma_street_address"
                          name="pma_street_address" class="form-control"></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6 col-sm-12">
                <div class="input-wrapper" style="background: #0a0a0a !important;">
                    <label for="pma_phone1" class="pma_input_label">Primary / Mobile Phone<span
                                style="color:red;">*</span></label>
                    <input placeholder="Primary / Mobile Phone" required type="text" id="pma_phone1" name="pma_phone1"
                           class="form-control">
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <div class="input-wrapper" style="background: #0a0a0a !important;">
                    <label for="pma_phone2" class="pma_input_label">Home / Secondary Phone</label>
                    <input placeholder="Home / Secondary Phone" type="text" id="pma_phone2" name="pma_phone2"
                           class="form-control">
                </div>
            </div>
        </div>
    <?php endif;
    if (strtolower($plan->post_title) !== 'curator'):

        ?>
        <div class="form-group">
            <div class="input-wrapper" style="background: #0a0a0a !important;">
                <label for="pma_dj_yoe" class="pma_input_label">Years of Experience as a DJ?<span
                            style="color:red;">*</span></label>
                <input placeholder="Years of Experience as a DJ?" type="number" required id="pma_dj_yoe"
                       name="pma_dj_yoe" class="form-control">
            </div>
        </div>
        <div class="form-group pma-addon-field">
            <div class="input-wrapper full-opacity" style="background: #0a0a0a !important;">
                <label for="pma_dj_type" style="font-weight: normal;" class="pma_input_label">What type of DJ are You?
                    (More
                    than one selection
                    possible)
                    <span style="color:red;">*</span></label>
                <div class="row checkboxes">
                    <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="row">
                            <label class="col-md-4" for="pma_dj_type_club">
                                <input class="pma_dj_type" data-dj-type="club" type="checkbox" name="pma_dj_type[]"
                                       value="club"
                                       id="pma_dj_type_club">
                                <span>Club</span>
                            </label>
                            <label class="col-md-4" for="pma_dj_type_mobile">
                                <input class="pma_dj_type" data-dj-type="mobile" type="checkbox" name="pma_dj_type[]"
                                       value="mobile"
                                       id="pma_dj_type_mobile">
                                <span>Mobile</span>
                            </label>
                            <label class="col-md-4" for="pma_dj_type_radio">
                                <input class="pma_dj_type" data-dj-type="radio" type="checkbox" name="pma_dj_type[]"
                                       value="radio"
                                       id="pma_dj_type_radio">
                                <span>Radio</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="row mt-1">
                            <label class="col-md-4" for="pma_dj_type_mixshow">
                                <input class="pma_dj_type" data-dj-type="mixshow" type="checkbox" name="pma_dj_type[]"
                                       value="mixshow"
                                       id="pma_dj_type_mixshow">
                                <span>Mixshow</span>
                            </label>
                            <label class="col-md-4" for="pma_dj_type_satellite">
                                <input class="pma_dj_type" data-dj-type="satellite" type="checkbox" name="pma_dj_type[]"
                                       value="satellite"
                                       id="pma_dj_type_satellite">
                                <span>Satellite</span>
                            </label>
                            <label class="col-md-4" for="pma_dj_type_internet">
                                <input class="pma_dj_type" data-dj-type="internet" type="checkbox" name="pma_dj_type[]"
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
    <div class="form-group ">
        <div class="input-wrapper" style="background: #0a0a0a !important;">
            <label for="pma_bio" class="pma_input_label">Bio/Additional supporting information<span
                        style="color:red;">*</span></label>
            <textarea placeholder="Bio/Additional supporting information" required id="pma_bio"
                      name="pma_bio" class="form-control" rows="4"></textarea>
        </div>
    </div>
    <div class="form-group pma-addon-field">
        <div class="input-wrapper" style="background: #0a0a0a !important;">
            <label for="pma_found_via" class="pma_input_label">How did you find out about RiddimStream?<span
                        style="color:red;">*</span></label>
            <select name="pma_found_via" id="pma_found_via" class="form-control" required>
                <option style="color:black!important;" value="">None</option>
                <option style="color:black!important;" value="Direct Email Marketing From">Direct Email Marketing From
                </option>
                <option style="color:black!important;" value="Internet Keyword Search">Internet Keyword Search</option>
                <option style="color:black!important;" value="Instagram Advertisement">Instagram Advertisement</option>
                <option style="color:black!important;" value="Facebook Advertisement">Facebook Advertisement</option>
                <option style="color:black!important;" value="Google Advertisement">Google Advertisement</option>
                <option style="color:black!important;" value="Word Of Mouth">Word Of Mouth</option>
                <option style="color:black!important;" value="Friend Recommendation">Friend Recommendation</option>
                <option style="color:black!important;" value="Other">Other</option>
            </select>
        </div>
    </div>
    <div class="input-wrapper p-0" style="background: none!important;border: none!important;">
        <label for="pma_marketing_consent" style="font-weight: normal;opacity: 1" class="pma_input_label">
            <input required type="checkbox" name="pma_marketing_consent"
                   id="pma_marketing_consent">
            <span style="color: white">I give my consent to RiddimStream Limited to be in touch with me via email using the information I have provided in this form for the purpose of news, updates and marketing.<span
                        style="color:red;">*</span></span>
        </label>
    </div>
    <div class="input-wrapper p-0" style="background: none!important;border: none!important;">
        <label for="pma_tnc" style="font-weight: normal;opacity: 1" class="pma_input_label">
            <input required type="checkbox" name="pma_tnc"
                   id="pma_tnc">
            <span style="color: white;">I agree to the terms and conditions mentioned <a
                        href="https://www.riddimstream.com/terms-conditions/"
                        rel="nofollow" target="_blank">here</a>.<span
                        style="color:red;">*</span></span>
        </label>
    </div>
    <br>
</div>
<span style="color: white;"><span style="color:red;">*</span> - marked fields are required.</span>
<div style="overflow:auto; margin-top: 30px;">
    <div style="float:right;">
        <button type="button" id="prevBtn" class="btn btn-primary ms-prevNext" onclick="nextPrev(-1)">Previous</button>
        <button type="button" id="nextBtn" class="btn btn-primary ms-prevNext" onclick="nextPrev(1)">Next</button>
    </div>
</div>
<br>


<?php if (is_user_logged_in()) : ?>
    <!-- render payment system selection -->
    <div id="coupon">
        <?php WPPM()->coupon_manager->renderCouponField() ?>
    </div>

    <div id='wppm-pms-selection'>
        <?php $wppm->payment_system_manager->renderPaymentSystemSelection(); ?>
    </div>

    <!-- Payment system interface to pay -->
    <div id="payment-system-interface" class="panel panel-default">

    </div>
<?php endif ?>

<br>
<div id="pma-errors" class="alert alert-danger" style="display:none"></div>
<div id="pma-success" class="alert alert-success" style="display:none"></div>


<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("step-tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:
        if (n === 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n === (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
            document.getElementById("nextBtn").className += " pma-submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
            document.getElementById("nextBtn").classList.remove("pma-submit");
        }
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("step-tab");
        if (n === 1 && !validateForm(currentTab)) return false;

        // if you have reached the end of the form... and you do not want to go back
        if (n > 0 && currentTab >= x.length - 1) {
            // ... the form gets submitted:
            submitForm();
        } else {
            // Hide the current tab
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            //show the next tab
            jQuery('#pma-errors').html('').css('display', 'none');
            showTab(currentTab);
        }
    }

    function validateForm(step) {
        var x, y, i, valid = true;
        x = document.getElementsByClassName("step-tab");
        y = x[currentTab].getElementsByTagName("input");

        if (step === 1) {
            valid = false;
            var socials = document.getElementsByClassName("pma_social");

            for (var idx = 0; idx < socials.length; idx++) {
                if (socials[idx].value !== "") {
                    if (!isValidHttpUrl(socials[idx].value, idx)) {
                        showErrors({errors: ['Input a correct url.']});
                        valid = false;
                        break;
                    } else {
                        valid = true
                    }
                }

                if (!valid && (idx === socials.length - 1)) {
                    showErrors({errors: ['At least one URL should be provided']});
                    valid = false;
                }
            }
        }


        // This function deals with validation of the form fields

        if (step === 0) {
            if (!validate_confirm_pass()) return false;
        }

        if (step === 2) {
            var checkboxes = document.querySelectorAll(".pma_dj_type");
            if (checkboxes.length !== 0) {
                var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);

                if (!checkedOne) {
                    showErrors({errors: ['At least one DJ type should be selected.']});
                    return false;
                }
            }
            if (!document.getElementById('pma_marketing_consent').checked) {
                showErrors({errors: ['Must accept marketing agreement.']});
                return false;
            }
            if (!document.getElementById('pma_tnc').checked) {
                showErrors({errors: ['Must accept terms and conditions.']});
                return false;
            }
        }


        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a required field is empty...
            if (y[i].hasAttribute('required') && y[i].value === "") {
                // add an "invalid" class to the field:
                if (!y[i].closest('div.input-wrapper').classList.contains('invalid'))
                    y[i].closest('div.input-wrapper').className += " invalid";
                // and set the current valid status to false
                showErrors({errors: ['Missing required fields.']});

                valid = false;
            } else {
                y[i].closest('div.input-wrapper').classList.remove('invalid')
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
    }


    function submitForm() {
        var signupForm = jQuery('#checkout-form');

        //SIGNUP handler
        var signupBtn = jQuery('button.pma-submit');
        var signupBtnOriginalLabel = signupBtn.html();
        jQuery(this).removeClass('.pma-submit');

        var form_data = new FormData(signupForm[0]);
        if (jQuery('#pma_label_logo').length)
            form_data.append('pma_label_logo', jQuery('#pma_label_logo')[0].files[0]);

        signupBtn.html("<i class='fa fa-cog fa-spin'></i> Please Wait...");
        signupBtn.prop('disabled', true);

        jQuery.ajax({
            url: pma_localize.ajax_url + '?action=pma_signup',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            success: function (data) {
                // signupBtn.html(signupBtnOriginalLabel);
                // signupBtn.prop('disabled', false);
                location.reload(true);
            }
        }).fail(function (res) {
            var data = res.responseJSON;
            showErrors(data);
            signupBtn.html(signupBtnOriginalLabel);
            signupBtn.prop('disabled', false);

        });
    }

    function validate_confirm_pass() {
        if (jQuery('#pma_confirm_pass').val() !== jQuery('#pma_password').val()) {
            jQuery('#pma_confirm_pass').parent('.input-wrapper').addClass('input-error');
            showErrors({errors: ['Password didn\'t match']})
            return false;
        } else {
            jQuery('#pma_confirm_pass').parent('.input-wrapper').removeClass('input-error');
            return true;
        }
    }

    function showErrors(data) {
        var successDiv = jQuery("#pma-success");
        var errorsDiv = jQuery("#pma-errors");
        errorsDiv.html("").hide();
        successDiv.html("").hide();
        if (data && 'errors' in data) {
            errorsDiv.show();
            jQuery.each(data.errors, function (i, err) {
                errorsDiv.append("<li>" + err + "</li>");
            })
        }
    }

    function showSuccess(data) {
        var successDiv = jQuery("#pma-success");
        var errorsDiv = jQuery("#pma-errors");

        successDiv.html("").hide();
        errorsDiv.html("").hide();
        if (data && 'success' in data) {
            successDiv.show();
            jQuery.each(data.success, function (i, err) {
                successDiv.append("<li>" + err + "</li>");
            })
        }
    }

    function isValidHttpUrl(string, idx) {
        var regexp;
        if (idx === 0) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(string);
        }
        if (idx === 1) {
            regexp = /^(https?:\/\/){0,1}(www\.){0,1}facebook\.com/;
            return regexp.test(string);
        }
        if (idx === 2) {
            regexp = /^https?:\/\/(soundcloud\.com|snd\.sc)\/(.*)$/;
            return regexp.test(string);
        }
        if (idx === 3) {
            regexp = /^(http|https)\:\/\/(www.|)twitter.com\/.*/i;
            return regexp.test(string);
        }
        if (idx === 4) {
            regexp = /(https?)?:?(www)?instagram\.com/;
            return regexp.test(string);
        }
    }


</script>
<?php //do_action("register_form"); ?>
<?php //do_action("wpdm_register_form"); ?>
