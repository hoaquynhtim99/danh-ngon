<!-- BEGIN: main -->
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
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
		<col width="200px"/>
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td><strong>{LANG.tags}</strong></td>
				<td>
					<div class="tags-area">
						<!-- BEGIN: tags -->
						<div class="tag-item">
							<label>
								<input type="checkbox" name="tags[]" value="{TAGS}"{CHECKED}/> {TAGS}
							</label>
						</div>
						<!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
						<!-- END: tags -->
					</div>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.content_new_tags}</strong></td>
				<td><input type="text" name="tags_news" value="" class="txt-full"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.content_content}</strong></td>
				<td><textarea rows="3" class="txt-full" name="content">{DATA.content}</textarea></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" name="submit" value="{LANG.submit}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<!-- END: main -->
