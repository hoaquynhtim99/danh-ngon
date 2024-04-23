<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<form method="post">
	<table class="table table-hover table-striped table-bordered">
		<caption><strong><em class="fa fa-file-text-o"></em> {CAPTION}</strong></caption>
		<tbody>
		<tr>
			<td class="text-center">{LANG.enter_search_key}</td>
			<td>
				<select class="form-control" id="catids" name="catids">
					<option value="-1" disabled selected>{LANG.please_select}</option>
					<!-- BEGIN: cat -->
					<option value="{CAT.key}" {CAT.selected}>{CAT.title}</option>
					<!-- END: cat -->
				</select>
			</td>
		</tr>
		<tr>
			<td class="text-center">{LANG.name_authour}</td>
			<td>
				<select class="form-control" name="author_id" id="author_id">
					<option value="-1" disabled selected>{LANG.please_select}</option>
					<!-- BEGIN: author -->
					<option value="{AUTHOR.key}"{AUTHOR.selected}>{AUTHOR.name_author}</option>
					<!-- END: author -->
				</select>
			</td>
		</tr>
		<tr>
			<td class="text-center">{LANG.content_content}</td>
			<td><textarea name="content" rows="5" class="form-control">{DATA.content}</textarea></td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="2" class="text-center">
				<input type="hidden" name="submit" value="{NV_CHECK_SESSION}">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {LANG.action}</button>
			</td>
		</tr>
		</tfoot>
	</table>
</form>
<script>
	$(document).ready(function () {
		$('#catids').select2({
			placeholder: '{LANG.please_select}',
			allowClear: true,
			minimumInputLength: 3,
		});
		$('#author_id').select2({});
	});
</script>
<!-- END: main -->
