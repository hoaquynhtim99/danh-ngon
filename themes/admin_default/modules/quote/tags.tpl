<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.title}</td>
				<td>{LANG.tags_nums}</td>
				<td style="width:90px" class="center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr>
				<td>{ROW.title}</td>
				<td>{ROW.nums}</td>
				<td class="center">
					<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_tags('{ROW.id}');">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
	</table>
</form>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" name="levelform" id="levelform">
	<a name="addeditarea"></a>
	<table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:100px">{LANG.title}</td>
				<td class="center" style="width:10px"><span class="requie">*</span></td>
				<td><input type="text" name="title" value="{DATA.title}" style="width:350px"/> <input type="submit" name="submit" value="{LANG.submit}"/></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->
