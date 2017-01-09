<div class="password_change">
    
 <?php if(is_array($banks)) : foreach ($banks as $key => $bank) : ?>
    <dl class="dl-horizontal banklist" id="banklist<?php echo $key; ?>">
        <dd class="banklist_title">
            <section>
                <label class="input">
                    <i class="icon_append fa fa-times" data-bankid="<?php echo $key; ?>"></i>
                    <p class="label"><?php echo $bank['bank_name']; ?></p>
                </label>
            </section>
        </dd>
    </dl>
    <form>
        <dl class="dl-horizontal">
            <dt><?php esc_html_e( 'Bank name', 'listify_child' ); ?></dt>
            <dd>
                <section>
                    <label for="bank_name" class="input">
                        <i class="icon_append fa fa-university"></i>
                        <input type="text" name="bank_name" id="bank_name" value="<?php echo $bank['bank_name']; ?>">
                        <b class="tooltip tooltip-bottom-right">Enter Bank name</b>
                    </label>
                </section>
            </dd>
            <hr>
            <dt><?php esc_html_e( 'Bank Routing Number', 'listify_child' ); ?></dt>
            <dd>
                <section>
                    <label for="bank_routing" class="input">
                        <i class="icon_append fa fa-wifi"></i>
                        <input type="text" name="bank_routing" id="bank_routing" value="<?php echo $mysavingwallet->ccMasking($bank['bank_routing']); ?>">
                        <b class="tooltip tooltip-bottom-right">Bank Routing Number</b>
                    </label>
                </section>
            </dd>
            <hr>
            <dt><?php esc_html_e( 'Bank Account Number', 'listify_child' ); ?></dt>
            <dd>
                <section>
                    <label for="account_number" class="input">
                        <i class="icon_append fa fa-credit-card-alt"></i>
                        <input type="text" name="account_number" id="account_number" value="<?php echo $mysavingwallet->ccMasking($bank['account_number']); ?>">
                        <b class="tooltip tooltip-bottom-right">Bank Account Number</b>
                    </label>
                </section>
            </dd>
            <hr>
            <dt><?php esc_html_e( 'Account Type', 'listify_child' ); ?></dt>
            <dd>
                <section>
                    <label class="input">
                        <select name="account_type">
                            <option value="checking" <?php if($bank['account_type'] == "checking") echo "selected"; ?>>Checking</option>
                            <option value="savings" <?php if($bank['account_type'] == "savings") echo "selected"; ?>>Savings</option>
                        </select>
                    </label>
                </section>
            </dd>
            <hr>
            <dt><?php esc_html_e( 'Support Doc', 'listify_child' ); ?></dt>
            <dd>
                <section>
                    <label for="async-upload" class="input">
                        <i class="icon_append fa fa-life-ring"></i>
                        <p class="image-notice"></p>
                        <input type="file" name="async-upload" id="async-upload" class="bank_docs" accept="image/*">
                        <input type="hidden" name="image_id" class="image_id">
                    </label>
                </section>
            </dd>
        </dl>       
    </form> 
        
    <?php endforeach; else : ?>
        <p> You have no payment info </p>
    <?php endif; ?>
</div>