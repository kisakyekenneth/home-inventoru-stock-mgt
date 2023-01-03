<table class="form-table">
	<tr>
        <th><label for="contact">User Account Status </label></th>
	    <td>
            <select name="kc-user-deactivated" id = "kc-user-deactivated">
                @if( $_kc_uipe_user_deactivated == 'yes')
                    <option value = ''>Active</option>
                    <option value = "yes"  selected>In Active</option>
                @else
                    <option value = ''  selected>Active</option>
                    <option value = "yes">In Active</option>
                @endif
            </select>
        </td>

	</tr>
</table>