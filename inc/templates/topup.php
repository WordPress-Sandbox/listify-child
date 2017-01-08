<div class="remodal creditcard_popup user_profile" data-remodal-id="topup" data-remodal-options="hashTracking: false">

<button data-remodal-action="close" class="remodal-close"></button>
<form id="topup" class="credit-card password_change">
    <div class="form-header">
        <h4 class="title">Credit Card Details</h4>
    </div>
    <div class="form-body">
        <dl class="dl-horizontal">
            <dd>
                <section>
                    <label for="card_number" class="input">
                        <i class="icon_append fa fa-cc-mastercard"></i>
                        <input name="card_number" type="text" placeholder="Card Number">
                        <b class="tooltip tooltip-bottom-right">Enter Card Number</b>
                    </label>
                </section>
            </dd>
            <dd>
                <section>
                    <label class="input form_inline">
                        <select name="month">
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="november">November</option>
                            <option value="december">December</option>
                        </select>
                        <select name="year">
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </label>
                </section>
            </dd>
            <dd>
                <section>
                    <label class="input">
                        <i class="icon_append fa fa-credit-card"></i>
                        <input type="text" name="cvv" placeholder="CVV">
                        <p class="cvv_help">3 or 4 digits usually found on the signature strip</p>
                    </label>
                </section>
            </dd>
        </dl>
        <!-- Buttons -->
        <button type="submit" class="proceed-btn button">Proceed</button>
        <p class="show_message"></p>
    </div>
</form>
</div>
