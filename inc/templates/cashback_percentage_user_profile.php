<h3>Cashback Percentage </h3>
<table class="form-table">
	<tr>
		<th><label for="twitter">Cashback Percentage</label></th>
		<td>
			<input type="text" name="cashback_percentage" id="cashback_percentage" value="<?php echo esc_attr( get_the_author_meta( 'cashback_percentage', $user->ID ) ); ?>" class="regular-text" /><br />
			<span class="description">Cashback Percentage this user can give</span>
		</td>
	</tr>
</table>